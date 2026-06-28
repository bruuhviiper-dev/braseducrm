<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\EventoCrm;
use Illuminate\Http\Request;

class EventoCrmController extends Controller
{
    public function index()
    {
        $eventos = EventoCrm::orderBy('nome')->paginate(20);
        return view('crm.eventos.index', compact('eventos'));
    }

    public function create()
    {
        return view('crm.eventos.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'icone' => 'nullable|string|max:255',
            'cor' => 'required|string|max:7',
        ]);
        $data['ativo'] = $request->boolean('ativo', true);
        EventoCrm::create($data);
        return redirect()->route('crm.eventos.index')->with('success', 'Evento criado com sucesso.');
    }

    public function edit(EventoCrm $evento)
    {
        return view('crm.eventos.form', compact('evento'));
    }

    public function update(Request $request, EventoCrm $evento)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'icone' => 'nullable|string|max:255',
            'cor' => 'required|string|max:7',
        ]);
        $data['ativo'] = $request->boolean('ativo');
        $evento->update($data);
        return redirect()->route('crm.eventos.index')->with('success', 'Evento atualizado com sucesso.');
    }

    public function destroy(EventoCrm $evento)
    {
        $evento->delete();
        return redirect()->route('crm.eventos.index')->with('success', 'Evento removido com sucesso.');
    }
}
