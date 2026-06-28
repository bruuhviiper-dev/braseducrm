<?php

namespace App\Http\Controllers;

use App\Models\Questao;
use App\Models\Questionario;

class GeralController extends Controller
{
    public function index()
    {
        $stats = [
            'questoes' => Questao::count(),
            'questionarios' => Questionario::count(),
        ];
        return view('geral.index', compact('stats'));
    }
}
