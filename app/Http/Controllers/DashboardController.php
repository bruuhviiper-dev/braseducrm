<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Atividade;
use App\Models\Funcao;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $favoritos = $user->favoritos()->get();

        $recentes = $user->acessosRecentes()->limit(10)->get();

        $atividadesHoje = Atividade::where('user_id', $user->id)
            ->whereDate('data_vencimento', today())
            ->where('situacao', '!=', 'concluida')
            ->count();

        $atividadesAtrasadas = Atividade::where('user_id', $user->id)
            ->where('data_vencimento', '<', now())
            ->where('situacao', '!=', 'concluida')
            ->count();

        $atividadesFuturas = Atividade::where('user_id', $user->id)
            ->where('data_vencimento', '>', now())
            ->where('situacao', '!=', 'concluida')
            ->count();

        $atividadesRecentes = Atividade::where('user_id', $user->id)
            ->where('situacao', '!=', 'concluida')
            ->orderBy('data_vencimento')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'favoritos', 'recentes',
            'atividadesHoje', 'atividadesAtrasadas', 'atividadesFuturas',
            'atividadesRecentes'
        ));
    }
}
