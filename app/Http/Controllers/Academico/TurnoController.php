<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Turno;
use Illuminate\Http\Request;

class TurnoController extends Controller
{
    public function index()
    {
        $turnos = Turno::orderBy('nome')->paginate(15);

        return view('academico.turnos.index', compact('turnos'));
    }

    public function create()
    {
        return view('academico.turnos.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        Turno::create($request->all());

        return redirect()->route('academico.turnos.index')
            ->with('success', 'Turno cadastrado com sucesso.');
    }

    public function edit(Turno $turno)
    {
        return view('academico.turnos.form', compact('turno'));
    }

    public function update(Request $request, Turno $turno)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $turno->update($request->all());

        return redirect()->route('academico.turnos.index')
            ->with('success', 'Turno atualizado com sucesso.');
    }

    public function destroy(Turno $turno)
    {
        $turno->delete();

        return redirect()->route('academico.turnos.index')
            ->with('success', 'Turno removido com sucesso.');
    }
}
