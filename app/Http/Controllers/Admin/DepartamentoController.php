<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    public function index()
    {
        $departamentos = Departamento::withCount('users')->orderBy('nome')->paginate(20);
        return view('admin.departamentos.index', compact('departamentos'));
    }

    public function create()
    {
        return view('admin.departamentos.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['nome' => 'required|string|max:255']);
        $data['ativo'] = $request->boolean('ativo', true);
        Departamento::create($data);
        return redirect()->route('admin.departamentos.index')->with('success', 'Departamento criado com sucesso.');
    }

    public function edit(Departamento $departamento)
    {
        return view('admin.departamentos.form', compact('departamento'));
    }

    public function update(Request $request, Departamento $departamento)
    {
        $data = $request->validate(['nome' => 'required|string|max:255']);
        $data['ativo'] = $request->boolean('ativo');
        $departamento->update($data);
        return redirect()->route('admin.departamentos.index')->with('success', 'Departamento atualizado com sucesso.');
    }

    public function destroy(Departamento $departamento)
    {
        $departamento->delete();
        return redirect()->route('admin.departamentos.index')->with('success', 'Departamento removido com sucesso.');
    }
}
