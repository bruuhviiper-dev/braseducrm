<?php

namespace App\Http\Controllers;

use App\Models\Oportunidade;
use App\Models\EtapaFunil;
use App\Models\TituloReceber;
use App\Models\TituloPagar;
use App\Models\Matricula;
use App\Models\Interessado;
use Illuminate\Support\Carbon;

class PainelController extends Controller
{
    public function comercial()
    {
        // Oportunidades por situação
        $porSituacao = Oportunidade::selectRaw('situacao, count(*) as total')
            ->groupBy('situacao')->pluck('total', 'situacao');

        // Oportunidades por etapa do funil
        $etapas = EtapaFunil::orderBy('ordem')->get();
        $porEtapa = [];
        foreach ($etapas as $etapa) {
            $porEtapa[$etapa->nome] = Oportunidade::where('etapa_funil_id', $etapa->id)
                ->where('situacao', 'aberta')->count();
        }

        // Valor por situação
        $valorGanho = Oportunidade::where('situacao', 'ganha')->sum('valor');
        $valorAberto = Oportunidade::where('situacao', 'aberta')->sum('valor');

        $stats = [
            'interessados' => Interessado::count(),
            'oportunidades' => Oportunidade::count(),
            'ganhas' => Oportunidade::where('situacao', 'ganha')->count(),
            'valor_ganho' => $valorGanho,
            'valor_aberto' => $valorAberto,
        ];

        return view('paineis.comercial', compact('porSituacao', 'porEtapa', 'stats'));
    }

    /** Painel Financeiro Geral (138): KPIs + A Receber / A Pagar + evolução (fiel ao EDUQ). */
    public function financeiro()
    {
        $hoje = now();

        // A RECEBER
        $recTotal = (float) TituloReceber::whereIn('situacao', ['aberto', 'pago'])->sum('valor_original');
        $recRecebidas = (float) TituloReceber::where('situacao', 'pago')->sum('valor_pago');
        $recVencidas = (float) TituloReceber::where('situacao', 'aberto')->whereDate('data_vencimento', '<', $hoje)->sum('valor_original');
        $recAVencer = (float) TituloReceber::where('situacao', 'aberto')->whereDate('data_vencimento', '>=', $hoje)->sum('valor_original');

        // A PAGAR
        $pagTotal = (float) TituloPagar::whereIn('situacao', ['aberto', 'pago'])->sum('valor_original');
        $pagPagas = (float) TituloPagar::where('situacao', 'pago')->sum('valor_pago');
        $pagVencidas = (float) TituloPagar::where('situacao', 'aberto')->whereDate('data_vencimento', '<', $hoje)->sum('valor_original');
        $pagAVencer = (float) TituloPagar::where('situacao', 'aberto')->whereDate('data_vencimento', '>=', $hoje)->sum('valor_original');

        $pct = fn ($parte, $total) => $total > 0 ? round($parte / $total * 100, 2) : 0;

        $kpis = [
            'resultado_previsto' => $recTotal - $pagTotal,
            'resultado_realizado' => $recRecebidas - $pagPagas,
            'taxa_realizada' => $pct($recRecebidas, $recTotal),
            'saldo_acumulado' => $recRecebidas - $pagPagas,
            'inadimplencia' => $pct($recVencidas, $recTotal),
        ];

        $aReceber = [
            'total' => $recTotal,
            'recebidas' => ['valor' => $recRecebidas, 'pct' => $pct($recRecebidas, $recTotal)],
            'vencidas' => ['valor' => $recVencidas, 'pct' => $pct($recVencidas, $recTotal)],
            'a_vencer' => ['valor' => $recAVencer, 'pct' => $pct($recAVencer, $recTotal)],
        ];
        $aPagar = [
            'total' => $pagTotal,
            'pagas' => ['valor' => $pagPagas, 'pct' => $pct($pagPagas, $pagTotal)],
            'vencidas' => ['valor' => $pagVencidas, 'pct' => $pct($pagVencidas, $pagTotal)],
            'a_vencer' => ['valor' => $pagAVencer, 'pct' => $pct($pagAVencer, $pagTotal)],
        ];

        // Evolução receitas x despesas (6 meses)
        $meses = [];
        $receitas = [];
        $despesas = [];
        for ($i = 5; $i >= 0; $i--) {
            $ref = Carbon::now()->subMonths($i);
            $meses[] = $ref->format('m/Y');
            $receitas[] = (float) TituloReceber::whereYear('data_vencimento', $ref->year)->whereMonth('data_vencimento', $ref->month)->sum('valor_original');
            $despesas[] = (float) TituloPagar::whereYear('data_vencimento', $ref->year)->whereMonth('data_vencimento', $ref->month)->sum('valor_original');
        }

        return view('paineis.financeiro', compact('kpis', 'aReceber', 'aPagar', 'meses', 'receitas', 'despesas'));
    }

    public function academico()
    {
        $porSituacao = Matricula::selectRaw('situacao, count(*) as total')
            ->groupBy('situacao')->pluck('total', 'situacao');

        $stats = [
            'matriculas' => Matricula::count(),
            'ativas' => Matricula::where('situacao', 'ativa')->count(),
            'concluidas' => Matricula::where('situacao', 'concluida')->count(),
        ];

        return view('paineis.academico', compact('porSituacao', 'stats'));
    }
}
