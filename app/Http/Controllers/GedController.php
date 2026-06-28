<?php

namespace App\Http\Controllers;

use App\Models\ClassificacaoGed;
use App\Models\DocumentoGed;
use App\Models\AtoRegulatorio;
use App\Models\DiplomaDigital;

class GedController extends Controller
{
    public function index()
    {
        $stats = [
            'classificacoes' => ClassificacaoGed::count(),
            'documentos' => DocumentoGed::count(),
            'atos' => AtoRegulatorio::count(),
            'diplomas' => DiplomaDigital::count(),
        ];
        return view('ged.index', compact('stats'));
    }
}
