<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GrupoOperador;
use App\Models\Funcao;
use Illuminate\Http\Request;

class GrupoOperadorController extends Controller
{
    public function index()
    {
        $grupos = GrupoOperador::withCount(['users', 'funcoes'])->orderBy('nome')->paginate(20);
        return view('admin.grupos.index', compact('grupos'));
    }

    public function create()
    {
        $funcoesPorModulo = Funcao::where('ativo', true)->orderBy('modulo')->orderBy('codigo')->get()->groupBy('modulo');
        return view('admin.grupos.form', compact('funcoesPorModulo'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'funcoes' => 'nullable|array',
            'funcoes.*' => 'exists:funcoes,id',
        ]);
        $grupo = GrupoOperador::create([
            'nome' => $data['nome'],
            'descricao' => $data['descricao'] ?? null,
            'ativo' => $request->boolean('ativo', true),
        ]);
        $grupo->funcoes()->sync($data['funcoes'] ?? []);
        return redirect()->route('admin.grupos.index')->with('success', 'Grupo criado com sucesso.');
    }

    public function edit(GrupoOperador $grupo)
    {
        $funcoesPorModulo = Funcao::where('ativo', true)->orderBy('modulo')->orderBy('codigo')->get()->groupBy('modulo');
        $selecionadas = $grupo->funcoes->pluck('id')->toArray();
        return view('admin.grupos.form', compact('grupo', 'funcoesPorModulo', 'selecionadas'));
    }

    public function update(Request $request, GrupoOperador $grupo)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'funcoes' => 'nullable|array',
            'funcoes.*' => 'exists:funcoes,id',
        ]);
        $grupo->update([
            'nome' => $data['nome'],
            'descricao' => $data['descricao'] ?? null,
            'ativo' => $request->boolean('ativo'),
        ]);
        $grupo->funcoes()->sync($data['funcoes'] ?? []);
        return redirect()->route('admin.grupos.index')->with('success', 'Grupo atualizado com sucesso.');
    }

    public function destroy(GrupoOperador $grupo)
    {
        $grupo->funcoes()->detach();
        $grupo->delete();
        return redirect()->route('admin.grupos.index')->with('success', 'Grupo removido com sucesso.');
    }
}
