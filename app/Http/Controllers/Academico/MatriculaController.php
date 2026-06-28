<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Matricula;
use App\Models\Aluno;
use App\Models\Turma;
use App\Models\FormaIngresso;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    public function index()
    {
        $matriculas = Matricula::with(['aluno.pessoa', 'turma'])->paginate(15);

        return view('academico.matriculas.index', compact('matriculas'));
    }

    public function create()
    {
        $alunos = Aluno::with('pessoa')->get();
        $turmas = Turma::orderBy('nome')->get();
        $formasIngresso = FormaIngresso::orderBy('nome')->get();

        return view('academico.matriculas.form', compact('alunos', 'turmas', 'formasIngresso'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
            'turma_id' => 'required|exists:turmas,id',
            'data_matricula' => 'required|date',
            'situacao' => 'required|string|max:50',
            'forma_ingresso_id' => 'nullable|exists:formas_ingresso,id',
            'observacoes' => 'nullable|string',
        ]);

        Matricula::create($request->all());

        return redirect()->route('academico.matriculas.index')
            ->with('success', 'Matricula realizada com sucesso.');
    }

    public function edit(Matricula $matricula)
    {
        $matricula->load('aluno.pessoa');
        $alunos = Aluno::with('pessoa')->get();
        $turmas = Turma::orderBy('nome')->get();
        $formasIngresso = FormaIngresso::orderBy('nome')->get();

        return view('academico.matriculas.form', compact('matricula', 'alunos', 'turmas', 'formasIngresso'));
    }

    public function update(Request $request, Matricula $matricula)
    {
        $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
            'turma_id' => 'required|exists:turmas,id',
            'data_matricula' => 'required|date',
            'situacao' => 'required|string|max:50',
            'forma_ingresso_id' => 'nullable|exists:formas_ingresso,id',
            'observacoes' => 'nullable|string',
        ]);

        $matricula->update($request->all());

        return redirect()->route('academico.matriculas.index')
            ->with('success', 'Matricula atualizada com sucesso.');
    }

    public function destroy(Matricula $matricula)
    {
        $matricula->delete();

        return redirect()->route('academico.matriculas.index')
            ->with('success', 'Matricula removida com sucesso.');
    }
}
