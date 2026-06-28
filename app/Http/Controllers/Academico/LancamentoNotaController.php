<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\TurmaMontada;
use App\Models\Disciplina;
use App\Models\TabelaAvaliacao;
use App\Models\Matricula;
use App\Models\Nota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LancamentoNotaController extends Controller
{
    public function index(Request $request)
    {
        $turmasMontadas = TurmaMontada::with('turma')->orderBy('id', 'desc')->get();
        $disciplinas = Disciplina::where('ativo', true)->orderBy('nome')->get();
        $tabelas = TabelaAvaliacao::with('itens')->orderBy('nome')->get();

        $grade = null;
        if ($request->filled(['turma_montada_id', 'disciplina_id', 'tabela_avaliacao_id'])) {
            $grade = $this->montarGrade($request);
        }

        return view('academico.lancamento-notas.index', compact('turmasMontadas', 'disciplinas', 'tabelas', 'grade', 'request'));
    }

    private function montarGrade(Request $request): array
    {
        $tabela = TabelaAvaliacao::with('itens')->findOrFail($request->tabela_avaliacao_id);
        $matriculas = Matricula::with('aluno.pessoa')
            ->where('turma_montada_id', $request->turma_montada_id)
            ->whereIn('situacao', ['ativa', 'concluida'])
            ->get();

        $notasExistentes = Nota::where('disciplina_id', $request->disciplina_id)
            ->whereIn('matricula_id', $matriculas->pluck('id'))
            ->get()
            ->groupBy(fn ($n) => $n->matricula_id . '-' . $n->tabela_avaliacao_item_id);

        return [
            'tabela' => $tabela,
            'matriculas' => $matriculas,
            'notas' => $notasExistentes,
        ];
    }

    public function salvar(Request $request)
    {
        $data = $request->validate([
            'turma_montada_id' => 'required|exists:turmas_montadas,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
            'tabela_avaliacao_id' => 'required|exists:tabelas_avaliacao,id',
            'notas' => 'nullable|array',
            // notas[matricula_id][item_id] = valor
        ]);

        DB::transaction(function () use ($data) {
            foreach (($data['notas'] ?? []) as $matriculaId => $itens) {
                foreach ($itens as $itemId => $valor) {
                    if ($valor === '' || $valor === null) {
                        continue;
                    }
                    Nota::updateOrCreate(
                        [
                            'matricula_id' => $matriculaId,
                            'disciplina_id' => $data['disciplina_id'],
                            'tabela_avaliacao_item_id' => $itemId,
                        ],
                        [
                            'nota' => $valor,
                            'situacao' => 'cursando',
                            'lancado_por' => auth()->id(),
                        ]
                    );
                }
            }
        });

        return redirect()->route('academico.lancamento-notas.index', $request->only(['turma_montada_id', 'disciplina_id', 'tabela_avaliacao_id']))
            ->with('success', 'Notas lançadas com sucesso.');
    }
}
