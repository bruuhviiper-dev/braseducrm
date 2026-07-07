<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\EtapaFunil;
use App\Models\Funil;
use App\Models\Indicacao;
use App\Models\Interessado;
use App\Models\Oportunidade;
use App\Models\OrigemInteressado;
use App\Models\TagCrm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OportunidadeController extends Controller
{
    public function index()
    {
        $oportunidades = Oportunidade::with(['interessado', 'funil', 'etapaFunil', 'consultor', 'curso'])
            ->orderBy('id', 'desc')->paginate(15);
        $motivosPerda = \App\Models\MotivoPerda::orderBy('nome')->get();

        return view('crm.oportunidades.index', compact('oportunidades', 'motivosPerda'));
    }

    /** EDUQ: "Ganho" significa efetivação de matrícula. */
    public function ganhar(Request $request, Oportunidade $oportunidade)
    {
        $v = $request->validate(['motivo_ganho_id' => 'nullable|exists:motivos_ganho,id']);
        $oportunidade->update([
            'situacao' => 'ganha',
            'motivo_ganho_id' => $v['motivo_ganho_id'] ?? null,
            'data_fechamento' => now(),
        ]);

        return back()->with('success', 'Oportunidade marcada como Ganha (efetivação de matrícula).');
    }

    /** EDUQ: a justificativa é obrigatória ao dar o card como perdido. */
    public function perder(Request $request, Oportunidade $oportunidade)
    {
        $v = $request->validate([
            'motivo_perda_id' => 'required|exists:motivos_perda,id',
        ], [
            'motivo_perda_id.required' => 'O motivo da perda é obrigatório (alimenta o gráfico de motivos de perda do painel comercial).',
        ]);

        $oportunidade->update([
            'situacao' => 'perdida',
            'motivo_perda_id' => $v['motivo_perda_id'],
            'data_fechamento' => now(),
        ]);

        return back()->with('success', 'Oportunidade marcada como Perdida.');
    }

    public function create()
    {
        return view('crm.oportunidades.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        DB::transaction(function () use ($data) {
            $op = Oportunidade::create($data['oportunidade']);
            $op->tags()->sync($data['tags']);
        });

        return redirect()->route('crm.oportunidades.index')->with('success', 'Oportunidade criada com sucesso.');
    }

    public function edit(Oportunidade $oportunidade)
    {
        $oportunidade->load('tags');

        return view('crm.oportunidades.form', $this->dados($oportunidade));
    }

    public function update(Request $request, Oportunidade $oportunidade)
    {
        $data = $this->validar($request);
        DB::transaction(function () use ($oportunidade, $data) {
            $oportunidade->update($data['oportunidade']);
            $oportunidade->tags()->sync($data['tags']);
        });

        return redirect()->route('crm.oportunidades.index')->with('success', 'Oportunidade atualizada com sucesso.');
    }

    public function destroy(Oportunidade $oportunidade)
    {
        $oportunidade->delete();

        return redirect()->route('crm.oportunidades.index')->with('success', 'Oportunidade removida com sucesso.');
    }

    public function moverEtapa(Request $request, Oportunidade $oportunidade)
    {
        $validated = $request->validate(['etapa_funil_id' => 'required|exists:etapas_funil,id']);
        $oportunidade->update(['etapa_funil_id' => $validated['etapa_funil_id']]);

        return response()->json(['success' => true]);
    }

    private function validar(Request $request): array
    {
        $v = $request->validate([
            'interessado_id' => 'required|exists:interessados,id',
            'origem_id' => 'nullable|exists:origens_interessado,id',
            'indicacao_id' => 'nullable|exists:indicacoes,id',
            'funil_id' => 'required|exists:funis,id',
            'etapa_funil_id' => 'required|exists:etapas_funil,id',
            'consultor_id' => 'nullable|exists:users,id',           // Responsável
            'curso_id' => 'nullable|exists:cursos,id',
            'titulo' => 'nullable|string|max:255',
            'valor' => 'nullable|numeric|min:0',
            'situacao' => 'nullable|in:aberta,ganha,perdida,pausada',
            'qualificacao' => 'nullable|in:quente,morno,frio',
            'data_previsao_fechamento' => 'nullable|date',
            'motivacao_interesse' => 'nullable|string',
            'observacoes' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags_crm,id',
            // EDUQ: a justificativa é obrigatória ao dar um card como perdido (alimenta o gráfico de motivos de perda)
            'motivo_perda_id' => 'required_if:situacao,perdida|nullable|exists:motivos_perda,id',
            'motivo_ganho_id' => 'nullable|exists:motivos_ganho,id',
        ], [
            'motivo_perda_id.required_if' => 'Ao marcar a oportunidade como Perdida, o motivo da perda é obrigatório (alimenta o painel comercial).',
        ]);

        return [
            'oportunidade' => [
                'interessado_id' => $v['interessado_id'],
                'origem_id' => $v['origem_id'] ?? null,
                'indicacao_id' => $v['indicacao_id'] ?? null,
                'funil_id' => $v['funil_id'],
                'etapa_funil_id' => $v['etapa_funil_id'],
                'consultor_id' => $v['consultor_id'] ?? null,
                'curso_id' => $v['curso_id'] ?? null,
                'titulo' => $v['titulo'] ?? null,
                'valor' => $v['valor'] ?? null,
                'situacao' => $v['situacao'] ?? 'aberta',
                'motivo_perda_id' => ($v['situacao'] ?? null) === 'perdida' ? ($v['motivo_perda_id'] ?? null) : null,
                'motivo_ganho_id' => ($v['situacao'] ?? null) === 'ganha' ? ($v['motivo_ganho_id'] ?? null) : null,
                'data_fechamento' => in_array($v['situacao'] ?? '', ['ganha', 'perdida']) ? now() : null,
                'qualificacao' => $v['qualificacao'] ?? null,
                'data_previsao_fechamento' => $v['data_previsao_fechamento'] ?? null,
                'motivacao_interesse' => $v['motivacao_interesse'] ?? null,
                'observacoes' => $v['observacoes'] ?? null,
            ],
            'tags' => array_map('intval', $v['tags'] ?? []),
        ];
    }

    private function dados(?Oportunidade $oportunidade): array
    {
        return [
            'oportunidade' => $oportunidade,
            'interessados' => Interessado::where('ativo', true)->orderBy('nome')->get(),
            'origens' => OrigemInteressado::orderBy('nome')->get(),
            'indicacoes' => Indicacao::orderByDesc('id')->get(),
            'funis' => Funil::where('ativo', true)->orderBy('nome')->get(),
            'etapas' => EtapaFunil::orderBy('ordem')->get(),
            'consultores' => User::where('ativo', true)->orderBy('nome')->get(),
            'cursos' => Curso::where('ativo', true)->orderBy('nome')->get(),
            'tagsList' => TagCrm::orderBy('nome')->get(),
        ];
    }
}
