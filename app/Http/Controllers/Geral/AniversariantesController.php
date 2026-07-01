<?php

namespace App\Http\Controllers\Geral;

use App\Http\Controllers\Controller;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AniversariantesController extends Controller
{
    public function index(Request $request)
    {
        $mes = (int) $request->get('mes', now()->month);

        $pessoas = Pessoa::whereNotNull('data_nascimento')
            ->whereRaw("CAST(strftime('%m', data_nascimento) AS INTEGER) = ?", [$mes])
            ->orderByRaw("CAST(strftime('%d', data_nascimento) AS INTEGER)")
            ->get();

        $meses = [1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril', 5 => 'Maio', 6 => 'Junho',
            7 => 'Julho', 8 => 'Agosto', 9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'];

        return view('geral.aniversariantes.index', compact('pessoas', 'mes', 'meses'));
    }
}
