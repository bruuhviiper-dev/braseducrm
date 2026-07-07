<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracaoCrm;
use App\Models\EtapaFunil;
use App\Models\Oportunidade;
use App\Models\RoletaOperador;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfiguracaoCrmController extends Controller
{
    public function index()
    {
        $config = ConfiguracaoCrm::current();
        $roleta = RoletaOperador::with('user')->orderBy('ordem')->get();
        $operadores = User::where('ativo', true)->orderBy('nome')->get();

        return view('crm.configuracoes.index', compact('config', 'roleta', 'operadores'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'roleta_ativa' => 'boolean',
            'dias_perda_automatica' => 'nullable|integer|min:0',
            'minutos_estagnacao' => 'nullable|integer|min:1',
            'rd_station_token' => 'nullable|string|max:255',
            'rd_station_url' => 'nullable|string|max:255',
            // roleta: operadores com proporção (A recebe 3, B recebe 2...)
            'roleta' => 'nullable|array',
            'roleta.*.user_id' => 'nullable|exists:users,id',
            'roleta.*.proporcao' => 'nullable|integer|min:1|max:99',
        ]);
        $data['roleta_ativa'] = $request->boolean('roleta_ativa');
        $data['considerar_dias_uteis'] = $request->boolean('considerar_dias_uteis');

        $config = ConfiguracaoCrm::current();
        $config->update(collect($data)->except('roleta')->all());

        // rebuild da roleta (ordem = posição na lista; prioriza os operadores do topo)
        DB::transaction(function () use ($data) {
            RoletaOperador::query()->delete();
            foreach (array_values($data['roleta'] ?? []) as $i => $r) {
                if (empty($r['user_id'])) {
                    continue;
                }
                RoletaOperador::create([
                    'user_id' => $r['user_id'],
                    'proporcao' => $r['proporcao'] ?: 1,
                    'ordem' => $i,
                    'ativo' => true,
                ]);
            }
        });

        return redirect()->route('crm.configuracoes.index')
            ->with('success', 'Configurações do CRM salvas com sucesso.');
    }

    /**
     * Automação contra estagnação (docs do EDUQ): leads abandonados numa etapa além do tempo
     * máximo são redistribuídos para o consultor seguinte da fila e voltam para a primeira etapa.
     */
    public function redistribuir()
    {
        $config = ConfiguracaoCrm::current();
        $minutos = (int) ($config->minutos_estagnacao ?: 20);

        if ($config->considerar_dias_uteis && now()->isWeekend()) {
            return back()->with('success', 'Roleta não executada: fim de semana (configurada para considerar apenas dias úteis).');
        }

        $estagnadas = Oportunidade::where('situacao', 'aberta')
            ->where('updated_at', '<', now()->subMinutes($minutos))
            ->get();

        $movidas = 0;
        foreach ($estagnadas as $op) {
            $proximo = self::proximoOperadorRoleta($op->consultor_id);
            if (!$proximo) {
                break; // roleta sem operadores
            }
            $primeiraEtapa = EtapaFunil::where('funil_id', $op->funil_id)->orderBy('ordem')->first();
            $op->update([
                'consultor_id' => $proximo,
                'etapa_funil_id' => $primeiraEtapa?->id ?? $op->etapa_funil_id,
            ]);
            $movidas++;
        }

        return back()->with('success', $movidas
            ? "Roleta executada: {$movidas} lead(s) estagnado(s) há mais de {$minutos} min redistribuído(s) e devolvido(s) ao primeiro contato."
            : 'Roleta executada: nenhum lead estagnado no momento.');
    }

    /** Próximo operador pela proporção (round-robin ponderado, priorizando o topo da lista). */
    public static function proximoOperadorRoleta(?int $exceto = null): ?int
    {
        $ops = RoletaOperador::where('ativo', true)->orderBy('ordem')->get();
        if ($ops->isEmpty()) {
            return null;
        }
        $candidatos = $ops->filter(fn ($o) => $o->user_id !== $exceto);
        if ($candidatos->isEmpty()) {
            $candidatos = $ops;
        }

        $escolhido = $candidatos->first(fn ($o) => $o->leads_ciclo < $o->proporcao);
        if (!$escolhido) {
            // ciclo completo: zera os contadores e recomeça do topo
            RoletaOperador::query()->update(['leads_ciclo' => 0]);
            $escolhido = $candidatos->first();
        }
        $escolhido->increment('leads_ciclo');

        return $escolhido->user_id;
    }
}
