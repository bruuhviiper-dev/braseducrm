<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\OrigemInteressado;
use Illuminate\Http\Request;

class OrigemController extends Controller
{
    public function index()
    {
        $origens = OrigemInteressado::orderBy('nome')->paginate(20);
        return view('crm.origens.index', compact('origens'));
    }

    public function create()
    {
        return view('crm.origens.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
        ]);
        $data['ativo'] = $request->boolean('ativo', true);
        OrigemInteressado::create($data);
        return redirect()->route('crm.origens.index')->with('success', 'Origem criada com sucesso.');
    }

    public function edit(OrigemInteressado $origem)
    {
        return view('crm.origens.form', compact('origem'));
    }

    public function update(Request $request, OrigemInteressado $origem)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
        ]);
        $data['ativo'] = $request->boolean('ativo');
        $origem->update($data);
        return redirect()->route('crm.origens.index')->with('success', 'Origem atualizada com sucesso.');
    }

    public function destroy(OrigemInteressado $origem)
    {
        $origem->delete();
        return redirect()->route('crm.origens.index')->with('success', 'Origem removida com sucesso.');
    }
}
