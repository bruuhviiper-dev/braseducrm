<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\ContaBancaria;
use Illuminate\Http\Request;

class ContaBancariaController extends Controller
{
    public function index()
    {
        $contas = ContaBancaria::orderBy('nome')->paginate(20);
        return view('financeiro.contas-bancarias.index', compact('contas'));
    }

    public function create()
    {
        return view('financeiro.contas-bancarias.form');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['ativo'] = $request->boolean('ativo', true);
        ContaBancaria::create($data);
        return redirect()->route('financeiro.contas-bancarias.index')->with('success', 'Conta bancária criada com sucesso.');
    }

    public function edit(ContaBancaria $contas_bancaria)
    {
        $conta = $contas_bancaria;
        return view('financeiro.contas-bancarias.form', compact('conta'));
    }

    public function update(Request $request, ContaBancaria $contas_bancaria)
    {
        $data = $this->validateData($request);
        $data['ativo'] = $request->boolean('ativo');
        $contas_bancaria->update($data);
        return redirect()->route('financeiro.contas-bancarias.index')->with('success', 'Conta bancária atualizada com sucesso.');
    }

    public function destroy(ContaBancaria $contas_bancaria)
    {
        $contas_bancaria->delete();
        return redirect()->route('financeiro.contas-bancarias.index')->with('success', 'Conta bancária removida com sucesso.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nome' => 'required|string|max:255',
            'banco' => 'nullable|string|max:255',
            'agencia' => 'nullable|string|max:50',
            'conta' => 'nullable|string|max:50',
            'tipo_conta' => 'nullable|string|max:50',
            'saldo_inicial' => 'nullable|numeric',
            'tesouraria' => 'nullable|boolean',
            'recebimento_caixa' => 'nullable|boolean',
            'eh_conta_bancaria' => 'nullable|boolean',
            'instituicao_ensino_id' => 'nullable|exists:instituicoes_ensino,id',
            'ignorar_novos_planos' => 'nullable|boolean',
            'ocultar_saldo_painel' => 'nullable|boolean',
            'desconsiderar_relatorios' => 'nullable|boolean',
            'descricao_resumida' => 'nullable|string|max:255',
            'data_saldo' => 'nullable|date',
        ]);
    }
}
