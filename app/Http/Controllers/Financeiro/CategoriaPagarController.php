<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\CategoriaPagar;
use App\Models\PlanoContas;
use Illuminate\Http\Request;

class CategoriaPagarController extends Controller
{
    public function index()
    {
        $categorias = CategoriaPagar::with('planoConta')->orderBy('nome')->paginate(20);
        return view('financeiro.categorias-pagar.index', compact('categorias'));
    }

    public function create()
    {
        $planos = PlanoContas::where('natureza', 'despesa')->orderBy('codigo')->get();
        return view('financeiro.categorias-pagar.form', compact('planos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'grupo' => 'nullable|string|max:255',
            'ativo' => 'nullable|boolean',
            'plano_conta_id' => 'nullable|exists:plano_contas,id',
        ]);
        CategoriaPagar::create($data);
        return redirect()->route('financeiro.categorias-pagar.index')->with('success', 'Categoria criada com sucesso.');
    }

    public function edit(CategoriaPagar $categorias_pagar)
    {
        $planos = PlanoContas::where('natureza', 'despesa')->orderBy('codigo')->get();
        $categoria = $categorias_pagar;
        return view('financeiro.categorias-pagar.form', compact('categoria', 'planos'));
    }

    public function update(Request $request, CategoriaPagar $categorias_pagar)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'grupo' => 'nullable|string|max:255',
            'ativo' => 'nullable|boolean',
            'plano_conta_id' => 'nullable|exists:plano_contas,id',
        ]);
        $categorias_pagar->update($data);
        return redirect()->route('financeiro.categorias-pagar.index')->with('success', 'Categoria atualizada com sucesso.');
    }

    public function destroy(CategoriaPagar $categorias_pagar)
    {
        $categorias_pagar->delete();
        return redirect()->route('financeiro.categorias-pagar.index')->with('success', 'Categoria removida com sucesso.');
    }
}
