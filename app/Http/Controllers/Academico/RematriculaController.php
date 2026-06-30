<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Matricula;
use App\Models\Rematricula;
use App\Models\Turma;
use Illuminate\Http\Request;

class RematriculaController extends Controller
{
    public function index()
    {
        $registros = Rematricula::with('matricula.aluno.pessoa', 'futuraTurma')->orderByDesc('id')->paginate(20);

        return view('academico.rematriculas.index', compact('registros'));
    }

    public function create()
    {
        return view('academico.rematriculas.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        Rematricula::create($this->validar($request));

        return redirect()->route('academico.rematriculas.index')->with('success', 'Rematrícula aberta com sucesso.');
    }

    public function edit(Rematricula $rematricula)
    {
        return view('academico.rematriculas.form', $this->dados($rematricula));
    }

    public function update(Request $request, Rematricula $rematricula)
    {
        $rematricula->update($this->validar($request));

        return redirect()->route('academico.rematriculas.index')->with('success', 'Rematrícula atualizada.');
    }

    public function destroy(Rematricula $rematricula)
    {
        $rematricula->delete();

        return redirect()->route('academico.rematriculas.index')->with('success', 'Rematrícula removida.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'matricula_id' => 'required|exists:matriculas,id',
            'futura_turma_id' => 'nullable|exists:turmas,id',
            'data_abertura' => 'nullable|date',
            'situacao' => 'required|in:' . implode(',', Rematricula::SITUACOES),
        ]);
    }

    private function dados(?Rematricula $registro): array
    {
        return [
            'registro' => $registro,
            'matriculas' => Matricula::with('aluno.pessoa')->orderByDesc('id')->get(),
            'turmas' => Turma::orderBy('nome')->get(),
        ];
    }
}
