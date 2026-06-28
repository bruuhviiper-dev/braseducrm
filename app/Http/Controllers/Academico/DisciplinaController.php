<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Disciplina;
use Illuminate\Http\Request;

class DisciplinaController extends Controller
{
    public function index()
    {
        $disciplinas = Disciplina::paginate(15);

        return view('academico.disciplinas.index', compact('disciplinas'));
    }

    public function create()
    {
        return view('academico.disciplinas.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'sigla' => 'required|string|max:20',
            'carga_horaria' => 'nullable|integer|min:0',
            'ementa' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['ativo'] = $request->has('ativo');

        Disciplina::create($data);

        return redirect()->route('academico.disciplinas.index')
            ->with('success', 'Disciplina cadastrada com sucesso.');
    }

    public function edit(Disciplina $disciplina)
    {
        return view('academico.disciplinas.form', compact('disciplina'));
    }

    public function update(Request $request, Disciplina $disciplina)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'sigla' => 'required|string|max:20',
            'carga_horaria' => 'nullable|integer|min:0',
            'ementa' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['ativo'] = $request->has('ativo');

        $disciplina->update($data);

        return redirect()->route('academico.disciplinas.index')
            ->with('success', 'Disciplina atualizada com sucesso.');
    }

    public function destroy(Disciplina $disciplina)
    {
        $disciplina->delete();

        return redirect()->route('academico.disciplinas.index')
            ->with('success', 'Disciplina removida com sucesso.');
    }
}
