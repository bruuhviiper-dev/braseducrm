<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\LancamentoFinanceiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FluxoCaixaController extends Controller
{
    public function index(Request $request)
    {
        $ano = $request->get('ano', date('Y'));

        $lancamentos = LancamentoFinanceiro::select(
                DB::raw("CAST(strftime('%m', data_lancamento) AS INTEGER) as mes"),
                DB::raw("SUM(CASE WHEN tipo = 'entrada' THEN valor ELSE 0 END) as total_receitas"),
                DB::raw("SUM(CASE WHEN tipo = 'saida' THEN valor ELSE 0 END) as total_despesas")
            )
            ->whereRaw("strftime('%Y', data_lancamento) = ?", [$ano])
            ->groupBy(DB::raw("strftime('%m', data_lancamento)"))
            ->orderBy('mes')
            ->get();

        $meses = [];
        $nomeMeses = [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Marco', 4 => 'Abril',
            5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
        ];

        for ($i = 1; $i <= 12; $i++) {
            $lancamento = $lancamentos->firstWhere('mes', $i);
            $meses[] = [
                'mes' => $i,
                'nome' => $nomeMeses[$i],
                'receitas' => $lancamento ? (float) $lancamento->total_receitas : 0,
                'despesas' => $lancamento ? (float) $lancamento->total_despesas : 0,
                'saldo' => $lancamento ? (float) $lancamento->total_receitas - (float) $lancamento->total_despesas : 0,
            ];
        }

        $totalReceitas = array_sum(array_column($meses, 'receitas'));
        $totalDespesas = array_sum(array_column($meses, 'despesas'));
        $saldoGeral = $totalReceitas - $totalDespesas;

        return view('financeiro.fluxo-caixa.index', compact(
            'meses', 'ano', 'totalReceitas', 'totalDespesas', 'saldoGeral'
        ));
    }
}
