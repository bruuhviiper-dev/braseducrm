<?php

namespace App\Http\Controllers\Ged;

use App\Http\Controllers\Controller;
use App\Models\DiplomaDigital;
use App\Models\Aluno;
use App\Models\Curso;
use Illuminate\Http\Request;

class DiplomaDigitalController extends Controller
{
    public function index()
    {
        $diplomas = DiplomaDigital::with(['aluno.pessoa', 'curso'])->orderBy('id', 'desc')->paginate(20);
        return view('ged.diplomas.index', compact('diplomas'));
    }

    public function create()
    {
        $alunos = Aluno::with('pessoa')->get();
        $cursos = Curso::orderBy('nome')->get();
        return view('ged.diplomas.form', compact('alunos', 'cursos'));
    }

    public function store(Request $request)
    {
        DiplomaDigital::create($this->validateData($request));
        return redirect()->route('ged.diplomas.index')->with('success', 'Diploma digital criado com sucesso.');
    }

    public function edit(DiplomaDigital $diploma)
    {
        $alunos = Aluno::with('pessoa')->get();
        $cursos = Curso::orderBy('nome')->get();
        return view('ged.diplomas.form', compact('diploma', 'alunos', 'cursos'));
    }

    public function update(Request $request, DiplomaDigital $diploma)
    {
        $diploma->update($this->validateData($request));
        return redirect()->route('ged.diplomas.index')->with('success', 'Diploma digital atualizado com sucesso.');
    }

    public function destroy(DiplomaDigital $diploma)
    {
        $diploma->delete();
        return redirect()->route('ged.diplomas.index')->with('success', 'Diploma digital removido com sucesso.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
            'curso_id' => 'nullable|exists:cursos,id',
            'numero_registro' => 'nullable|string|max:255',
            'situacao' => 'required|in:pendente,emitido,assinado,registrado',
            'data_emissao' => 'nullable|date',
            'data_colacao' => 'nullable|date',
            'observacoes' => 'nullable|string',
        ]);
    }
}
