<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\MatrizCurricular;
use App\Models\Curso;
use Illuminate\Http\Request;

class MatrizCurricularController extends Controller
{
    public function index()
    {
        $matrizes = MatrizCurricular::with('curso')->paginate(15);

        return view('academico.matrizes.index', compact('matrizes'));
    }

    public function create()
    {
        $cursos = Curso::orderBy('nome')->get();

        return view('academico.matrizes.form', compact('cursos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'curso_id' => 'required|exists:cursos,id',
            'carga_horaria_total' => 'nullable|integer|min:0',
            'situacao' => 'required|in:rascunho,ativa,finalizada',
            'observacoes' => 'nullable|string',
        ]);

        MatrizCurricular::create($request->all());

        return redirect()->route('academico.matrizes.index')
            ->with('success', 'Matriz curricular cadastrada com sucesso.');
    }

    public function edit(MatrizCurricular $matrize)
    {
        $cursos = Curso::orderBy('nome')->get();

        return view('academico.matrizes.form', ['matriz' => $matrize, 'cursos' => $cursos]);
    }

    public function update(Request $request, MatrizCurricular $matrize)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'curso_id' => 'required|exists:cursos,id',
            'carga_horaria_total' => 'nullable|integer|min:0',
            'situacao' => 'required|in:rascunho,ativa,finalizada',
            'observacoes' => 'nullable|string',
        ]);

        $matrize->update($request->all());

        return redirect()->route('academico.matrizes.index')
            ->with('success', 'Matriz curricular atualizada com sucesso.');
    }

    public function destroy(MatrizCurricular $matrize)
    {
        $matrize->delete();

        return redirect()->route('academico.matrizes.index')
            ->with('success', 'Matriz curricular removida com sucesso.');
    }
}
