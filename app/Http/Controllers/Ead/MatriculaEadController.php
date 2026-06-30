<?php

namespace App\Http\Controllers\Ead;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\CursoEad;
use App\Models\MatriculaEad;
use Illuminate\Http\Request;

class MatriculaEadController extends Controller
{
    public function index()
    {
        $matriculas = MatriculaEad::with('aluno.pessoa', 'cursoEad')->orderByDesc('id')->paginate(20);

        return view('ead.matriculas.index', compact('matriculas'));
    }

    public function create()
    {
        return view('ead.matriculas.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        MatriculaEad::create($this->validar($request));

        return redirect()->route('ead.matriculas.index')->with('success', 'Matrícula EAD criada com sucesso.');
    }

    public function edit(MatriculaEad $matricula)
    {
        return view('ead.matriculas.form', $this->dados($matricula));
    }

    public function update(Request $request, MatriculaEad $matricula)
    {
        $matricula->update($this->validar($request));

        return redirect()->route('ead.matriculas.index')->with('success', 'Matrícula EAD atualizada.');
    }

    public function destroy(MatriculaEad $matricula)
    {
        $matricula->delete();

        return redirect()->route('ead.matriculas.index')->with('success', 'Matrícula EAD removida.');
    }

    private function validar(Request $request): array
    {
        $data = $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
            'curso_ead_id' => 'required|exists:cursos_ead,id',
            'data_matricula' => 'required|date',
            'situacao' => 'required|in:ativa,concluida,cancelada,trancada',
        ]);
        $data['ativo'] = $request->boolean('ativo');
        $data['permitir_inadimplente'] = $request->boolean('permitir_inadimplente');

        return $data;
    }

    private function dados(?MatriculaEad $matricula): array
    {
        return [
            'matricula' => $matricula,
            'alunos' => Aluno::with('pessoa')->get(),
            'cursos' => CursoEad::orderBy('nome')->get(),
        ];
    }
}
