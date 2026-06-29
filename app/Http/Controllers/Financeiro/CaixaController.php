<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\Caixa;
use App\Models\ContaBancaria;
use Illuminate\Http\Request;

class CaixaController extends Controller
{
    public function index()
    {
        $caixas = Caixa::with('contaBancaria')->withCount('movimentacoes')
            ->orderByDesc('data_abertura')->paginate(20);
        $contas = ContaBancaria::where('ativo', true)->orderBy('nome')->get();
        return view('financeiro.caixas.index', compact('caixas', 'contas'));
    }

    public function abrir(Request $request)
    {
        $data = $request->validate([
            'conta_bancaria_id' => 'nullable|exists:contas_bancarias,id',
            'valor_abertura' => 'required|numeric|min:0',
            'observacoes' => 'nullable|string',
        ]);
        $data['data_abertura'] = now();
        $data['situacao'] = 'aberto';
        $data['operador_id'] = auth()->id();
        $caixa = Caixa::create($data);
        return redirect()->route('financeiro.caixas.show', $caixa)->with('success', 'Caixa aberto com sucesso.');
    }

    public function show(Caixa $caixa)
    {
        $caixa->load(['contaBancaria', 'movimentacoes' => fn ($q) => $q->latest()]);
        return view('financeiro.caixas.show', compact('caixa'));
    }

    public function movimentar(Request $request, Caixa $caixa)
    {
        abort_if($caixa->situacao !== 'aberto', 403, 'Caixa não está aberto.');
        $data = $request->validate([
            'tipo' => 'required|in:entrada,saida,sangria,suprimento',
            'valor' => 'required|numeric|min:0.01',
            'descricao' => 'required|string|max:255',
            'forma_pagamento' => 'required|in:dinheiro,cartao_debito,cartao_credito,pix,cheque',
        ]);
        $caixa->movimentacoes()->create($data);
        return back()->with('success', 'Movimentação registrada.');
    }

    public function fechar(Caixa $caixa)
    {
        abort_if($caixa->situacao !== 'aberto', 403, 'Caixa já está fechado.');
        $caixa->update([
            'situacao' => 'fechado',
            'data_fechamento' => now(),
            'valor_fechamento' => $caixa->saldoAtual(),
        ]);
        return back()->with('success', 'Caixa fechado. Saldo final: R$ ' . number_format($caixa->valor_fechamento, 2, ',', '.'));
    }
}
