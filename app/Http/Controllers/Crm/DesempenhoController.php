<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Oportunidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DesempenhoController extends Controller
{
    public function index()
    {
        $consultores = User::where('ativo', true)
            ->whereHas('atividades')
            ->orWhereIn('id', Oportunidade::select('consultor_id')->distinct())
            ->orderBy('nome')
            ->get()
            ->map(function ($consultor) {
                $oportunidades = Oportunidade::where('consultor_id', $consultor->id);

                $total = (clone $oportunidades)->count();
                $ganhas = (clone $oportunidades)->where('situacao', 'ganha')->count();
                $perdidas = (clone $oportunidades)->where('situacao', 'perdida')->count();
                $abertas = (clone $oportunidades)->where('situacao', 'aberta')->count();
                $valorTotalGanho = (clone $oportunidades)->where('situacao', 'ganha')->sum('valor');
                $taxaConversao = $total > 0 ? round(($ganhas / $total) * 100, 1) : 0;

                $consultor->stats = (object) [
                    'total' => $total,
                    'ganhas' => $ganhas,
                    'perdidas' => $perdidas,
                    'abertas' => $abertas,
                    'valor_total_ganho' => $valorTotalGanho,
                    'taxa_conversao' => $taxaConversao,
                ];

                return $consultor;
            })
            ->filter(fn($c) => $c->stats->total > 0);

        // Totals
        $totalGeral = Oportunidade::count();
        $ganhasGeral = Oportunidade::where('situacao', 'ganha')->count();
        $perdidasGeral = Oportunidade::where('situacao', 'perdida')->count();
        $valorGeral = Oportunidade::where('situacao', 'ganha')->sum('valor');

        return view('crm.desempenho.index', compact('consultores', 'totalGeral', 'ganhasGeral', 'perdidasGeral', 'valorGeral'));
    }
}
