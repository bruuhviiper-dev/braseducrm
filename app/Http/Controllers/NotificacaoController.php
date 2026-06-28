<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notificacao;

class NotificacaoController extends Controller
{
    public function index(Request $request)
    {
        $query = Notificacao::where('user_id', Auth::id())->orderByDesc('created_at');

        if ($request->has('filtro')) {
            if ($request->filtro === 'lidas') {
                $query->where('lida', true);
            } elseif ($request->filtro === 'nao_lidas') {
                $query->where('lida', false);
            }
        }

        $notificacoes = $query->paginate(20);
        $totalNaoLidas = Notificacao::where('user_id', Auth::id())->where('lida', false)->count();

        return view('notificacoes.index', compact('notificacoes', 'totalNaoLidas'));
    }

    public function marcarLida(Notificacao $notificacao)
    {
        if ($notificacao->user_id !== Auth::id()) {
            abort(403);
        }

        $notificacao->update(['lida' => true]);

        return back()->with('success', 'Notificacao marcada como lida.');
    }
}
