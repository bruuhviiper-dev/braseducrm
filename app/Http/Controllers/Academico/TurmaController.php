<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Turma;
use App\Models\Curso;
use App\Models\MatrizCurricular;
use App\Models\Turno;
use App\Models\PeriodoLetivo;
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
        $cursos = Curso::orderBy('nome')->get();
        $matrizes = MatrizCurricular::orderBy('nome')->get();
        $turnos = Turno::orderBy('nome')->get();
        $periodos = PeriodoLetivo::orderBy('nome')->get();

        return view('academico.turmas.form', compact('cursos', 'matrizes', 'turnos', 'periodos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'codigo' => 'required|string|max:50',
            'curso_id' => 'required|exists:cursos,id',
            'matriz_curricular_id' => 'nullable|exists:matrizes_curriculares,id',
            'turno_id' => 'nullable|exists:turnos,id',
            'periodo_letivo_id' => 'nullable|exists:periodos_letivos,id',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'vagas' => 'nullable|integer|min:0',
            'situacao' => 'required|string|max:50',
        ]);

        Turma::create($request->all());

        return redirect()->route('academico.turmas.index')
            ->with('success', 'Turma cadastrada com sucesso.');
    }

    public function edit(Turma $turma)
    {
        $cursos = Curso::orderBy('nome')->get();
        $matrizes = MatrizCurricular::orderBy('nome')->get();
        $turnos = Turno::orderBy('nome')->get();
        $periodos = PeriodoLetivo::orderBy('nome')->get();

        return view('academico.turmas.form', compact('turma', 'cursos', 'matrizes', 'turnos', 'periodos'));
    }

    public function update(Request $request, Turma $turma)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'codigo' => 'required|string|max:50',
            'curso_id' => 'required|exists:cursos,id',
            'matriz_curricular_id' => 'nullable|exists:matrizes_curriculares,id',
            'turno_id' => 'nullable|exists:turnos,id',
            'periodo_letivo_id' => 'nullable|exists:periodos_letivos,id',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'vagas' => 'nullable|integer|min:0',
            'situacao' => 'required|string|max:50',
        ]);

        $turma->update($request->all());

        return redirect()->route('academico.turmas.index')
            ->with('success', 'Turma atualizada com sucesso.');
    }

    public function destroy(Turma $turma)
    {
        $turma->delete();

        return redirect()->route('academico.turmas.index')
            ->with('success', 'Turma removida com sucesso.');
    }
}
