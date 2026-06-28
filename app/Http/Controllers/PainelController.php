<?php

namespace App\Http\Controllers;

use App\Models\Oportunidade;
use App\Models\EtapaFunil;
use App\Models\TituloReceber;
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

    public function financeiro()
    {
        // Receber vs Pago nos últimos 6 meses
        $meses = [];
        $receber = [];
        $pago = [];
        for ($i = 5; $i >= 0; $i--) {
            $ref = Carbon::now()->subMonths($i);
            $label = $ref->format('m/Y');
            $meses[] = $label;
            $receber[] = (float) TituloReceber::whereYear('data_vencimento', $ref->year)
                ->whereMonth('data_vencimento', $ref->month)->sum('valor_original');
            $pago[] = (float) TituloReceber::where('situacao', 'pago')
                ->whereYear('data_pagamento', $ref->year)
                ->whereMonth('data_pagamento', $ref->month)->sum('valor_pago');
        }

        $stats = [
            'total_aberto' => TituloReceber::where('situacao', 'aberto')->sum('valor_original'),
            'total_pago' => TituloReceber::where('situacao', 'pago')->sum('valor_pago'),
            'total_vencido' => TituloReceber::where('situacao', 'aberto')->where('data_vencimento', '<', now())->sum('valor_original'),
        ];

        return view('paineis.financeiro', compact('meses', 'receber', 'pago', 'stats'));
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
