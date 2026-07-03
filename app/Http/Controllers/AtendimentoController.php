<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use App\Models\CategoriaAtendimento;
use App\Models\Pessoa;
use Illuminate\Http\Request;

class AtendimentoController extends Controller
{
    public function index()
    {
        $atendimentos = Atendimento::with(['pessoa', 'categoria', 'operador'])
            ->orderBy('id', 'desc')->paginate(20);
        return view('administrativo.atendimentos.index', compact('atendimentos'));
    }

    public function create()
    {
        $pessoas = Pessoa::orderBy('nome')->get();
        $categorias = CategoriaAtendimento::orderBy('nome')->get();
        $responsaveis = \App\Models\User::where('ativo', true)->orderBy('nome')->get();
        return view('administrativo.atendimentos.form', compact('pessoas', 'categorias', 'responsaveis'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['operador_id'] = auth()->id();
        Atendimento::create($data);
        return redirect()->route('atendimentos.index')->with('success', 'Atendimento registrado com sucesso.');
    }

    public function edit(Atendimento $atendimento)
    {
        $pessoas = Pessoa::orderBy('nome')->get();
        $categorias = CategoriaAtendimento::orderBy('nome')->get();
        $responsaveis = \App\Models\User::where('ativo', true)->orderBy('nome')->get();
        return view('administrativo.atendimentos.form', compact('atendimento', 'pessoas', 'categorias', 'responsaveis'));
    }

    public function update(Request $request, Atendimento $atendimento)
    {
        $data = $this->validateData($request);
        $atendimento->update($data);
        return redirect()->route('atendimentos.index')->with('success', 'Atendimento atualizado com sucesso.');
    }

    public function destroy(Atendimento $atendimento)
    {
        $atendimento->delete();
        return redirect()->route('atendimentos.index')->with('success', 'Atendimento removido com sucesso.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'pessoa_id' => 'required|exists:pessoas,id',
            'categoria_atendimento_id' => 'nullable|exists:categorias_atendimento,id',
            'descricao' => 'required|string',
            'situacao' => 'nullable|in:aberto,em_andamento,concluido,falha',
            'responsavel_id' => 'nullable|exists:users,id',
            'canal' => 'nullable|string|max:100',
            'portal_aluno' => 'nullable|boolean',
            'precisa_retorno' => 'nullable|boolean',
            'departamentos_responsavel' => 'nullable|boolean',
            'resolucao' => 'nullable|string',
        ]);
    }
}
