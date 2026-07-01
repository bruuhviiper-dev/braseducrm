<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Oportunidade;
use Barryvdh\DomPDF\Facade\Pdf;

class PropostaCrmController extends Controller
{
    public function index()
    {
        $oportunidades = Oportunidade::with(['interessado', 'produtoServico', 'consultor'])
            ->orderByDesc('id')->paginate(20);

        return view('crm.propostas.index', compact('oportunidades'));
    }

    public function gerar(Oportunidade $oportunidade)
    {
        $oportunidade->load(['interessado', 'produtoServico', 'consultor', 'curso']);

        $pdf = Pdf::loadView('crm.propostas.pdf', compact('oportunidade'))->setPaper('a4', 'portrait');

        return $pdf->stream('proposta_' . $oportunidade->id . '.pdf');
    }
}
