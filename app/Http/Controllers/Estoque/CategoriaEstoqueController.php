<?php

namespace App\Http\Controllers\Estoque;

use App\Http\Controllers\Controller;
use App\Models\CategoriaEstoque;
use Illuminate\Http\Request;

class CategoriaEstoqueController extends Controller
{
    public function index()
    {
        $categorias = CategoriaEstoque::orderBy('nome')->paginate(20);
        return view('estoque.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('estoque.categorias.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
        ]);
        CategoriaEstoque::create($data);
        return redirect()->route('estoque.categorias.index')->with('success', 'Categoria criada com sucesso.');
    }

    public function edit(CategoriaEstoque $categoria)
    {
        return view('estoque.categorias.form', compact('categoria'));
    }

    public function update(Request $request, CategoriaEstoque $categoria)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
        ]);
        $categoria->update($data);
        return redirect()->route('estoque.categorias.index')->with('success', 'Categoria atualizada com sucesso.');
    }

    public function destroy(CategoriaEstoque $categoria)
    {
        $categoria->delete();
        return redirect()->route('estoque.categorias.index')->with('success', 'Categoria removida com sucesso.');
    }
}
