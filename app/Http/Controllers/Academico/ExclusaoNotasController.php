<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Disciplina;
use App\Models\Frequencia;
use App\Models\Nota;
use App\Models\TurmaMontada;
use Illuminate\Http\Request;

/**
 * Exclusão de Notas e Faltas (137 do EDUQ): apaga em lote os lançamentos de uma
 * turma montada × disciplina para permitir relançamento do zero (função administrativa).
 */
class ExclusaoNotasController extends Controller
{
    public function index(Request $request)
    {
        $turmasMontadas = TurmaMontada::with('turma')->orderBy('id', 'desc')->get();
        $disciplinas = Disciplina::where('ativo', true)->orderBy('nome')->get();

        $previa = null;
        if ($request->filled(['turma_montada_id', 'disciplina_id'])) {
            $matriculaIds = \App\Models\Matricula::where('turma_montada_id', $request->turma_montada_id)->pluck('id');
            $previa = [
                'notas' => Nota::whereIn('matricula_id', $matriculaIds)->where('disciplina_id', $request->disciplina_id)->count(),
                'faltas' => Frequencia::whereIn('matricula_id', $matriculaIds)->where('disciplina_id', $request->disciplina_id)->count(),
            ];
        }

        return view('academico.exclusao-notas.index', compact('turmasMontadas', 'disciplinas', 'previa', 'request'));
    }

    public function excluir(Request $request)
    {
        $v = $request->validate([
            'turma_montada_id' => 'required|exists:turmas_montadas,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
            'excluir_notas' => 'nullable|boolean',
            'excluir_faltas' => 'nullable|boolean',
        ]);

        if (!$request->boolean('excluir_notas') && !$request->boolean('excluir_faltas')) {
            return back()->withInput()->withErrors(['excluir_notas' => 'Marque o que deve ser excluído: notas, faltas ou ambos.']);
        }

        $matriculaIds = \App\Models\Matricula::where('turma_montada_id', $v['turma_montada_id'])->pluck('id');
        $notas = $faltas = 0;
        if ($request->boolean('excluir_notas')) {
            $notas = Nota::whereIn('matricula_id', $matriculaIds)->where('disciplina_id', $v['disciplina_id'])->delete();
        }
        if ($request->boolean('excluir_faltas')) {
            $faltas = Frequencia::whereIn('matricula_id', $matriculaIds)->where('disciplina_id', $v['disciplina_id'])->delete();
        }

        return redirect()->route('academico.exclusao-notas.index')
            ->with('success', "Exclusão concluída: {$notas} nota(s) e {$faltas} registro(s) de frequência removidos. Os lançamentos podem ser refeitos do zero.");
    }
}
