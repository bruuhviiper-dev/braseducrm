<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Atividade;

class CalendarioController extends Controller
{
    public function index(Request $request)
    {
        $mes = $request->get('mes', now()->month);
        $ano = $request->get('ano', now()->year);

        $atividades = Atividade::where('user_id', Auth::id())
            ->whereMonth('data_vencimento', $mes)
            ->whereYear('data_vencimento', $ano)
            ->orderBy('data_vencimento')
            ->get();

        return view('calendario.index', compact('atividades', 'mes', 'ano'));
    }
}
