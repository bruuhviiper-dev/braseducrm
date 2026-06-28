<?php

namespace App\Http\Controllers\MatriculaOnline;

use App\Http\Controllers\Controller;
use App\Models\AberturaMatriculaOnline;
use App\Models\Curso;
use Illuminate\Http\Request;

class AberturaController extends Controller
{
    public function index()
    {
        $aberturas = AberturaMatriculaOnline::with('curso')->withCount('inscricoes')
            ->orderBy('data_inicio', 'desc')->paginate(20);
        return view('matricula-online.aberturas.index', compact('aberturas'));
    }

    public function create()
    {
        $cursos = Curso::where('ativo', true)->orderBy('nome')->get();
        return view('matricula-online.aberturas.form', compact('cursos'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['ativo'] = $request->boolean('ativo', true);
        AberturaMatriculaOnline::create($data);
        return redirect()->route('matricula-online.aberturas.index')->with('success', 'Abertura criada com sucesso.');
    }

    public function edit(AberturaMatriculaOnline $abertura)
    {
        $cursos = Curso::where('ativo', true)->orderBy('nome')->get();
        return view('matricula-online.aberturas.form', compact('abertura', 'cursos'));
    }

    public function update(Request $request, AberturaMatriculaOnline $abertura)
    {
        $data = $this->validateData($request);
        $data['ativo'] = $request->boolean('ativo');
        $abertura->update($data);
        return redirect()->route('matricula-online.aberturas.index')->with('success', 'Abertura atualizada com sucesso.');
    }

    public function destroy(AberturaMatriculaOnline $abertura)
    {
        $abertura->delete();
        return redirect()->route('matricula-online.aberturas.index')->with('success', 'Abertura removida com sucesso.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nome' => 'required|string|max:255',
            'curso_id' => 'nullable|exists:cursos,id',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'valor_matricula' => 'nullable|numeric|min:0',
            'valor_curso' => 'nullable|numeric|min:0',
            'vagas' => 'nullable|integer|min:0',
            'descricao' => 'nullable|string',
        ]);
    }
}
