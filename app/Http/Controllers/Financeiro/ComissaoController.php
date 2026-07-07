<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\Matricula;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * 222 - Cálculo de Comissões (regra dos docs do EDUQ):
 * o vendedor fica fixado na matrícula e o percentual é editável LIVREMENTE por venda
 * ("como uma folha de Excel"), recalculando o valor líquido no fechamento.
 * Base de cálculo: valor da matrícula (taxa de inscrição).
 */
class ComissaoController extends Controller
{
    public function index(Request $request)
    {
        $query = Matricula::with(['aluno.pessoa', 'turma.curso', 'consultor'])
            ->orderByDesc('data_matricula');

        if ($request->filled('consultor_id')) {
            $query->where('consultor_id', $request->consultor_id);
        }
        if ($request->filled('de')) {
            $query->where('data_matricula', '>=', $request->de);
        }
        if ($request->filled('ate')) {
            $query->where('data_matricula', '<=', $request->ate);
        }

        $matriculas = $query->paginate(30)->withQueryString();
        $consultores = User::where('ativo', true)->orderBy('nome')->get();

        // fechamento: total de comissões do filtro atual
        $totalBase = 0.0;
        $totalComissao = 0.0;
        foreach ($matriculas as $m) {
            $base = (float) ($m->valor_total ?? 0);
            $perc = $m->comissao_percentual !== null ? (float) $m->comissao_percentual : (float) ($m->turma?->curso?->valor_comissao ?? 0);
            $totalBase += $base;
            $totalComissao += $base * $perc / 100;
        }

        return view('financeiro.comissoes.index', compact('matriculas', 'consultores', 'totalBase', 'totalComissao'));
    }

    /** Salva em lote o vendedor + percentual por matrícula (edição livre, linha a linha). */
    public function salvar(Request $request)
    {
        $data = $request->validate([
            'linhas' => 'required|array',
            'linhas.*.consultor_id' => 'nullable|exists:users,id',
            'linhas.*.percentual' => 'nullable|numeric|min:0|max:100',
        ]);

        $alteradas = 0;
        foreach ($data['linhas'] as $matriculaId => $l) {
            $m = Matricula::find($matriculaId);
            if (!$m) {
                continue;
            }
            $m->update([
                'consultor_id' => $l['consultor_id'] ?: null,
                'comissao_percentual' => $l['percentual'] !== null && $l['percentual'] !== '' ? $l['percentual'] : null,
            ]);
            $alteradas++;
        }

        return back()->with('success', "Comissões atualizadas em {$alteradas} matrícula(s). O relatório de fechamento já reflete os novos valores.");
    }
}
