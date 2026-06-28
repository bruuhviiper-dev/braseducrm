<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\CategoriaReceber;
use App\Models\PlanoContas;
use Illuminate\Http\Request;

class CategoriaReceberController extends Controller
{
    public function index()
    {
        $categorias = CategoriaReceber::with('planoConta')->orderBy('nome')->paginate(20);
        return view('financeiro.categorias-receber.index', compact('categorias'));
    }

    public function create()
    {
        $planos = PlanoContas::where('natureza', 'receita')->orderBy('codigo')->get();
        return view('financeiro.categorias-receber.form', compact('planos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'plano_conta_id' => 'nullable|exists:plano_contas,id',
        ]);
        CategoriaReceber::create($data);
        return redirect()->route('financeiro.categorias-receber.index')->with('success', 'Categoria criada com sucesso.');
    }

    public function edit(CategoriaReceber $categorias_receber)
    {
        $planos = PlanoContas::where('natureza', 'receita')->orderBy('codigo')->get();
        $categoria = $categorias_receber;
        return view('financeiro.categorias-receber.form', compact('categoria', 'planos'));
    }

    public function update(Request $request, CategoriaReceber $categorias_receber)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'plano_conta_id' => 'nullable|exists:plano_contas,id',
        ]);
        $categorias_receber->update($data);
        return redirect()->route('financeiro.categorias-receber.index')->with('success', 'Categoria atualizada com sucesso.');
    }

    public function destroy(CategoriaReceber $categorias_receber)
    {
        $categorias_receber->delete();
        return redirect()->route('financeiro.categorias-receber.index')->with('success', 'Categoria removida com sucesso.');
    }
}
