<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Sala;
use Illuminate\Http\Request;

class SalaController extends Controller
{
    public function index()
    {
        $salas = Sala::orderBy('nome')->paginate(15);

        return view('academico.salas.index', compact('salas'));
    }

    public function create()
    {
        return view('academico.salas.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'capacidade' => 'nullable|integer|min:0',
            'bloco' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        $data['ativo'] = $request->has('ativo');

        Sala::create($data);

        return redirect()->route('academico.salas.index')
            ->with('success', 'Sala cadastrada com sucesso.');
    }

    public function edit(Sala $sala)
    {
        return view('academico.salas.form', compact('sala'));
    }

    public function update(Request $request, Sala $sala)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'capacidade' => 'nullable|integer|min:0',
            'bloco' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        $data['ativo'] = $request->has('ativo');

        $sala->update($data);

        return redirect()->route('academico.salas.index')
            ->with('success', 'Sala atualizada com sucesso.');
    }

    public function destroy(Sala $sala)
    {
        $sala->delete();

        return redirect()->route('academico.salas.index')
            ->with('success', 'Sala removida com sucesso.');
    }
}
