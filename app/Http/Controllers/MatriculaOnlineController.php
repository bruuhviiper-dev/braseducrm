<?php

namespace App\Http\Controllers;

use App\Models\AberturaMatriculaOnline;
use App\Models\Inscricao;
use App\Models\CupomDesconto;

class MatriculaOnlineController extends Controller
{
    public function index()
    {
        $stats = [
            'aberturas_ativas' => AberturaMatriculaOnline::where('ativo', true)->count(),
            'inscricoes_total' => Inscricao::count(),
            'inscricoes_pendentes' => Inscricao::where('situacao', 'pendente')->count(),
            'inscricoes_matriculadas' => Inscricao::where('situacao', 'matriculada')->count(),
            'cupons_ativos' => CupomDesconto::where('ativo', true)->count(),
        ];

        $aberturas = AberturaMatriculaOnline::withCount('inscricoes')
            ->orderBy('data_inicio', 'desc')->take(5)->get();

        $ultimasInscricoes = Inscricao::with('abertura')
            ->orderBy('id', 'desc')->take(8)->get();

        return view('matricula-online.index', compact('stats', 'aberturas', 'ultimasInscricoes'));
    }
}
