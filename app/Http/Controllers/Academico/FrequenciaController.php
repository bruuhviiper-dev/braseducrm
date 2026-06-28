<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\TurmaMontada;
use App\Models\Disciplina;
use App\Models\Matricula;
use App\Models\Frequencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrequenciaController extends Controller
{
    public function index(Request $request)
    {
        $turmasMontadas = TurmaMontada::with('turma')->orderBy('id', 'desc')->get();
        $disciplinas = Disciplina::where('ativo', true)->orderBy('nome')->get();

        $roster = null;
        if ($request->filled(['turma_montada_id', 'disciplina_id', 'data'])) {
            $matriculas = Matricula::with('aluno.pessoa')
                ->where('turma_montada_id', $request->turma_montada_id)
                ->whereIn('situacao', ['ativa', 'concluida'])
                ->get();

            $registros = Frequencia::where('disciplina_id', $request->disciplina_id)
                ->where('data', $request->data)
                ->whereIn('matricula_id', $matriculas->pluck('id'))
                ->get()->keyBy('matricula_id');

            $conteudo = $registros->first()->conteudo_ministrado ?? '';

            $roster = compact('matriculas', 'registros', 'conteudo');
        }

        return view('academico.frequencia.index', compact('turmasMontadas', 'disciplinas', 'roster', 'request'));
    }

    public function salvar(Request $request)
    {
        $data = $request->validate([
            'turma_montada_id' => 'required|exists:turmas_montadas,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
            'data' => 'required|date',
            'conteudo_ministrado' => 'nullable|string',
            'status' => 'nullable|array',
            // status[matricula_id] = presente|ausente|justificada
        ]);

        DB::transaction(function () use ($data) {
            foreach (($data['status'] ?? []) as $matriculaId => $status) {
                Frequencia::updateOrCreate(
                    [
                        'matricula_id' => $matriculaId,
                        'disciplina_id' => $data['disciplina_id'],
                        'data' => $data['data'],
                    ],
                    [
                        'status' => $status,
                        'conteudo_ministrado' => $data['conteudo_ministrado'] ?? null,
                        'lancado_por' => auth()->id(),
                    ]
                );
            }
        });

        return redirect()->route('academico.frequencia.index', $request->only(['turma_montada_id', 'disciplina_id', 'data']))
            ->with('success', 'Frequência registrada com sucesso.');
    }
}
