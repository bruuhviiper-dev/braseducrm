<?php

namespace App\Http\Controllers\Estoque;

use App\Http\Controllers\Controller;
use App\Models\ProdutoEstoque;
use App\Models\CategoriaEstoque;
use App\Models\UnidadeMedida;
use Illuminate\Http\Request;

class ProdutoEstoqueController extends Controller
{
    public function index()
    {
        $produtos = ProdutoEstoque::with('categoriaEstoque', 'unidadeMedida')->orderBy('nome')->paginate(20);
        return view('estoque.produtos.index', compact('produtos'));
    }

    public function create()
    {
        $categorias = CategoriaEstoque::orderBy('nome')->get();
        $unidades = UnidadeMedida::orderBy('nome')->get();
        return view('estoque.produtos.form', compact('categorias', 'unidades'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:255|unique:produtos_estoque',
            'categoria_estoque_id' => 'nullable|exists:categorias_estoque,id',
            'unidade_medida_id' => 'nullable|exists:unidades_medida,id',
            'preco_custo' => 'nullable|numeric|min:0',
            'estoque_minimo' => 'integer|min:0',
            'ativo' => 'boolean',
        ]);
        $data['ativo'] = $request->has('ativo');
        ProdutoEstoque::create($data);
        return redirect()->route('estoque.produtos.index')->with('success', 'Produto criado com sucesso.');
    }

    public function edit(ProdutoEstoque $produto)
    {
        $categorias = CategoriaEstoque::orderBy('nome')->get();
        $unidades = UnidadeMedida::orderBy('nome')->get();
        return view('estoque.produtos.form', compact('produto', 'categorias', 'unidades'));
    }

    public function update(Request $request, ProdutoEstoque $produto)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:255|unique:produtos_estoque,codigo,' . $produto->id,
            'categoria_estoque_id' => 'nullable|exists:categorias_estoque,id',
            'unidade_medida_id' => 'nullable|exists:unidades_medida,id',
            'preco_custo' => 'nullable|numeric|min:0',
            'estoque_minimo' => 'integer|min:0',
            'ativo' => 'boolean',
        ]);
        $data['ativo'] = $request->has('ativo');
        $produto->update($data);
        return redirect()->route('estoque.produtos.index')->with('success', 'Produto atualizado com sucesso.');
    }

    public function destroy(ProdutoEstoque $produto)
    {
        $produto->delete();
        return redirect()->route('estoque.produtos.index')->with('success', 'Produto removido com sucesso.');
    }
}
