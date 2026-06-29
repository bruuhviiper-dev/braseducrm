<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\TituloReceber;
use App\Models\TituloPagar;
use App\Models\LancamentoFinanceiro;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DreController extends Controller
{
    public function index(Request $request)
    {
        $inicio = $request->filled('inicio') ? Carbon::parse($request->inicio) : now()->startOfMonth();
        $fim = $request->filled('fim') ? Carbon::parse($request->fim) : now()->endOfMonth();

        // Receitas: títulos recebidos (pagos) no período + lançamentos de entrada
        $receitasTitulos = (float) TituloReceber::where('situacao', 'pago')
            ->whereBetween('data_pagamento', [$inicio, $fim])->sum('valor_pago');
        $receitasLancamentos = (float) LancamentoFinanceiro::where('tipo', 'entrada')
            ->whereBetween('data_lancamento', [$inicio, $fim])->sum('valor');

        // Despesas: títulos a pagar quitados + lançamentos de saída
        $despesasTitulos = (float) TituloPagar::where('situacao', 'pago')
            ->whereBetween('data_pagamento', [$inicio, $fim])->sum('valor_pago');
        $despesasLancamentos = (float) LancamentoFinanceiro::where('tipo', 'saida')
            ->whereBetween('data_lancamento', [$inicio, $fim])->sum('valor');

        $totalReceitas = $receitasTitulos + $receitasLancamentos;
        $totalDespesas = $despesasTitulos + $despesasLancamentos;
        $resultado = $totalReceitas - $totalDespesas;

        $dre = compact(
            'receitasTitulos', 'receitasLancamentos', 'despesasTitulos',
            'despesasLancamentos', 'totalReceitas', 'totalDespesas', 'resultado'
        );

        return view('financeiro.dre.index', [
            'dre' => $dre,
            'inicio' => $inicio->format('Y-m-d'),
            'fim' => $fim->format('Y-m-d'),
        ]);
    }
}
