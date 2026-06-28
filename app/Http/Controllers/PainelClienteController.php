<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstituicaoEnsino;
use App\Models\Aluno;
use App\Models\Curso;
use App\Models\Turma;

class PainelClienteController extends Controller
{
    public function index()
    {
        $instituicao = InstituicaoEnsino::first();
        $totalAlunos = Aluno::count();
        $totalCursos = Curso::count();
        $totalTurmasAtivas = Turma::where('situacao', 'em_andamento')->count();

        return view('painel-cliente.index', compact(
            'instituicao', 'totalAlunos', 'totalCursos', 'totalTurmasAtivas'
        ));
    }
}
