<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\ContaBancaria;
use App\Models\ContratoCartao;
use Illuminate\Http\Request;

class ContratoCartaoController extends Controller
{
    public function index()
    {
        $contratos = ContratoCartao::with('contaBancaria')->orderBy('operadora')->paginate(20);

        return view('financeiro.contratos-cartao.index', compact('contratos'));
    }

    public function create()
    {
        return view('financeiro.contratos-cartao.form', ['contrato' => null, 'contas' => $this->contas()]);
    }

    public function store(Request $request)
    {
        ContratoCartao::create($this->validar($request));

        return redirect()->route('financeiro.contratos-cartao.index')->with('success', 'Contrato de cartão criado.');
    }

    public function edit(ContratoCartao $contratos_cartao)
    {
        return view('financeiro.contratos-cartao.form', ['contrato' => $contratos_cartao, 'contas' => $this->contas()]);
    }

    public function update(Request $request, ContratoCartao $contratos_cartao)
    {
        $contratos_cartao->update($this->validar($request));

        return redirect()->route('financeiro.contratos-cartao.index')->with('success', 'Contrato atualizado.');
    }

    public function destroy(ContratoCartao $contratos_cartao)
    {
        $contratos_cartao->delete();

        return redirect()->route('financeiro.contratos-cartao.index')->with('success', 'Contrato removido.');
    }

    private function validar(Request $request): array
    {
        $data = $request->validate([
            'operadora' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:255',
            'conta_bancaria_id' => 'nullable|exists:contas_bancarias,id',
            'taxa_debito' => 'nullable|numeric|min:0|max:100',
            'taxa_credito_vista' => 'nullable|numeric|min:0|max:100',
            'taxa_credito_parcelado' => 'nullable|numeric|min:0|max:100',
            'prazo_recebimento_dias' => 'nullable|integer|min:0',
            'ativo' => 'boolean',
        ]);
        $data['taxa_debito'] = $data['taxa_debito'] ?? 0;
        $data['taxa_credito_vista'] = $data['taxa_credito_vista'] ?? 0;
        $data['taxa_credito_parcelado'] = $data['taxa_credito_parcelado'] ?? 0;
        $data['prazo_recebimento_dias'] = $data['prazo_recebimento_dias'] ?? 30;
        $data['ativo'] = $request->boolean('ativo');

        return $data;
    }

    private function contas()
    {
        return ContaBancaria::orderBy('nome')->get();
    }
}
