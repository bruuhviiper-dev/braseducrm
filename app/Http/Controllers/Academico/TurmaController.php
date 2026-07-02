<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\InstituicaoEnsino;
use App\Models\MatrizCurricular;
use App\Models\Turma;
use App\Models\Turno;
use Illuminate\Http\Request;

class TurmaController extends Controller
{
    public function index()
    {
        $turmas = Turma::with(['curso', 'turno', 'periodoLetivo'])->paginate(15);

        return view('academico.turmas.index', compact('turmas'));
    }

    public function create()
    {
        return view('academico.turmas.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        Turma::create($this->validar($request));

        return redirect()->route('academico.turmas.index')
            ->with('success', 'Turma cadastrada com sucesso.');
    }

    public function edit(Turma $turma)
    {
        return view('academico.turmas.form', $this->dados($turma));
    }

    public function update(Request $request, Turma $turma)
    {
        $turma->update($this->validar($request));

        return redirect()->route('academico.turmas.index')
            ->with('success', 'Turma atualizada com sucesso.');
    }

    public function destroy(Turma $turma)
    {
        $turma->delete();

        return redirect()->route('academico.turmas.index')
            ->with('success', 'Turma removida com sucesso.');
    }

    private function validar(Request $request): array
    {
        $v = $request->validate([
            'codigo' => 'required|string|max:50',                 // SIGLA
            'nome' => 'required|string|max:255',                  // Descrição
            'instituicao_ensino_id' => 'required|exists:instituicoes_ensino,id',
            'matriz_curricular_id' => 'required|exists:matrizes_curriculares,id',
            'turno_id' => 'required|exists:turnos,id',
            'vagas' => 'nullable|integer|min:0',                  // Quantidade máxima de alunos
        ]);

        // Curso deriva da Matriz Curricular (EDUQ não pede curso direto na turma)
        $matriz = MatrizCurricular::find($v['matriz_curricular_id']);
        $finalizada = $request->boolean('finalizada');

        return [
            'codigo' => $v['codigo'],
            'nome' => $v['nome'],
            'instituicao_ensino_id' => $v['instituicao_ensino_id'] ?? null,
            'matriz_curricular_id' => $v['matriz_curricular_id'],
            'curso_id' => $matriz?->curso_id,
            'turno_id' => $v['turno_id'] ?? null,
            'vagas' => $v['vagas'] ?? null,
            'finalizada' => $finalizada,
            // EDUQ usa toggle "Turma finalizada?"; derivamos a situacao textual p/ compat do schema
            'situacao' => $finalizada ? 'finalizada' : 'ativa',
        ];
    }

    private function dados(?Turma $turma): array
    {
        if ($turma) {
            $turma->loadCount(['matriculas', 'turmasMontadas']);
            $turma->load('matriculas.aluno.pessoa', 'turmasMontadas.periodoLetivo');
        }

        return [
            'turma' => $turma,
            'instituicoes' => InstituicaoEnsino::orderBy('nome')->get(),
            'matrizes' => MatrizCurricular::with('curso')->orderBy('nome')->get(),
            'turnos' => Turno::orderBy('nome')->get(),
        ];
    }
}
