<?php

namespace App\Http\Controllers\Ead;

use App\Http\Controllers\Controller;
use App\Models\CursoEad;
use Illuminate\Http\Request;

class CursoEadController extends Controller
{
    public function index()
    {
        $cursos = CursoEad::orderBy('nome')->paginate(20);
        return view('ead.cursos.index', compact('cursos'));
    }

    public function create()
    {
        return view('ead.cursos.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'carga_horaria' => 'nullable|integer|min:0',
            'valor' => 'nullable|numeric|min:0',
            'ativo' => 'boolean',
        ]);
        $data['ativo'] = $request->has('ativo');
        CursoEad::create($data);
        return redirect()->route('ead.cursos.index')->with('success', 'Curso EAD criado com sucesso.');
    }

    public function edit(CursoEad $curso)
    {
        return view('ead.cursos.form', compact('curso'));
    }

    public function update(Request $request, CursoEad $curso)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'carga_horaria' => 'nullable|integer|min:0',
            'valor' => 'nullable|numeric|min:0',
            'ativo' => 'boolean',
        ]);
        $data['ativo'] = $request->has('ativo');
        $curso->update($data);
        return redirect()->route('ead.cursos.index')->with('success', 'Curso EAD atualizado com sucesso.');
    }

    public function destroy(CursoEad $curso)
    {
        $curso->delete();
        return redirect()->route('ead.cursos.index')->with('success', 'Curso EAD removido com sucesso.');
    }
}
