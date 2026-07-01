<?php

namespace App\Http\Controllers\Geral;

use App\Http\Controllers\Controller;
use App\Models\ConsultaPersonalizada;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ConsultaPersonalizadaController extends Controller
{
    public function index()
    {
        $consultas = ConsultaPersonalizada::orderByDesc('id')->paginate(20);

        return view('geral.consultas.index', compact('consultas'));
    }

    public function create()
    {
        return view('geral.consultas.form', ['consulta' => null, 'entidades' => ConsultaPersonalizada::entidades()]);
    }

    public function store(Request $request)
    {
        ConsultaPersonalizada::create($this->validar($request));

        return redirect()->route('geral.consultas.index')->with('success', 'Consulta salva.');
    }

    public function edit(ConsultaPersonalizada $consulta)
    {
        return view('geral.consultas.form', ['consulta' => $consulta, 'entidades' => ConsultaPersonalizada::entidades()]);
    }

    public function update(Request $request, ConsultaPersonalizada $consulta)
    {
        $consulta->update($this->validar($request));

        return redirect()->route('geral.consultas.index')->with('success', 'Consulta atualizada.');
    }

    public function destroy(ConsultaPersonalizada $consulta)
    {
        $consulta->delete();

        return redirect()->route('geral.consultas.index')->with('success', 'Consulta removida.');
    }

    /** Executa a consulta e mostra os resultados. */
    public function executar(ConsultaPersonalizada $consulta)
    {
        [$cfg, $campos, $rows] = $this->rodar($consulta);

        return view('geral.consultas.resultado', compact('consulta', 'cfg', 'campos', 'rows'));
    }

    /** Exporta o resultado em CSV. */
    public function exportar(ConsultaPersonalizada $consulta): StreamedResponse
    {
        [$cfg, $campos, $rows] = $this->rodar($consulta, 5000);

        return response()->streamDownload(function () use ($cfg, $campos, $rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, array_map(fn ($c) => $cfg['campos'][$c], $campos), ';');
            foreach ($rows as $r) {
                fputcsv($out, array_map(fn ($c) => $r->{$c}, $campos), ';');
            }
            fclose($out);
        }, 'consulta_' . $consulta->id . '.csv');
    }

    /** Roda a consulta com segurança (whitelist de entidade/campos/operador). */
    private function rodar(ConsultaPersonalizada $consulta, int $limite = 500): array
    {
        $entidades = ConsultaPersonalizada::entidades();
        abort_unless(isset($entidades[$consulta->entidade]), 404);
        $cfg = $entidades[$consulta->entidade];

        // apenas campos que existem no whitelist
        $campos = array_values(array_intersect($consulta->campos ?? [], array_keys($cfg['campos'])));
        if (empty($campos)) {
            $campos = array_slice(array_keys($cfg['campos']), 0, 3);
        }

        $query = $cfg['model']::query();

        // filtro (campo precisa estar no whitelist)
        if ($consulta->filtro_campo && array_key_exists($consulta->filtro_campo, $cfg['campos']) && $consulta->filtro_valor !== null && $consulta->filtro_valor !== '') {
            $valor = $consulta->filtro_valor;
            match ($consulta->filtro_operador) {
                'igual' => $query->where($consulta->filtro_campo, $valor),
                'maior' => $query->where($consulta->filtro_campo, '>', $valor),
                'menor' => $query->where($consulta->filtro_campo, '<', $valor),
                default => $query->where($consulta->filtro_campo, 'like', '%' . $valor . '%'),
            };
        }

        $rows = $query->limit($limite)->get($campos);

        return [$cfg, $campos, $rows];
    }

    private function validar(Request $request): array
    {
        $entidades = ConsultaPersonalizada::entidades();
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'entidade' => 'required|in:' . implode(',', array_keys($entidades)),
            'campos' => 'nullable|array',
            'filtro_campo' => 'nullable|string|max:255',
            'filtro_operador' => 'nullable|in:' . implode(',', array_keys(ConsultaPersonalizada::OPERADORES)),
            'filtro_valor' => 'nullable|string|max:255',
        ]);

        // sanitiza campos ao whitelist da entidade
        $permitidos = array_keys($entidades[$data['entidade']]['campos']);
        $data['campos'] = array_values(array_intersect($data['campos'] ?? [], $permitidos));
        if (!in_array($data['filtro_campo'], $permitidos, true)) {
            $data['filtro_campo'] = null;
        }
        $data['ativo'] = $request->boolean('ativo', true);

        return $data;
    }
}
