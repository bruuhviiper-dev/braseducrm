<?php

namespace App\Http\Controllers\Estoque;

use App\Http\Controllers\Controller;
use App\Models\UnidadeMedida;
use Illuminate\Http\Request;

class UnidadeMedidaController extends Controller
{
    public function index()
    {
        $unidades = UnidadeMedida::orderBy('nome')->paginate(20);
        return view('estoque.unidades.index', compact('unidades'));
    }

    public function create()
    {
        return view('estoque.unidades.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'sigla' => 'required|string|max:10',
        ]);
        UnidadeMedida::create($data);
        return redirect()->route('estoque.unidades.index')->with('success', 'Unidade de medida criada com sucesso.');
    }

    public function edit(UnidadeMedida $unidade)
    {
        return view('estoque.unidades.form', compact('unidade'));
    }

    public function update(Request $request, UnidadeMedida $unidade)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'sigla' => 'required|string|max:10',
        ]);
        $unidade->update($data);
        return redirect()->route('estoque.unidades.index')->with('success', 'Unidade de medida atualizada com sucesso.');
    }

    public function destroy(UnidadeMedida $unidade)
    {
        $unidade->delete();
        return redirect()->route('estoque.unidades.index')->with('success', 'Unidade de medida removida com sucesso.');
    }
}
