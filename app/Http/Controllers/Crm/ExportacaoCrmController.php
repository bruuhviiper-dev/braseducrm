<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Oportunidade;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportacaoCrmController extends Controller
{
    public function index()
    {
        $total = Oportunidade::count();

        return view('crm.exportacao.index', compact('total'));
    }

    public function exportar(Request $request): StreamedResponse
    {
        $situacao = $request->get('situacao');

        $query = Oportunidade::with(['interessado', 'produtoServico', 'consultor', 'etapaFunil']);
        if ($situacao) {
            $query->where('situacao', $situacao);
        }

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="oportunidades.csv"',
        ];

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM UTF-8 para Excel
            fputcsv($out, ['ID', 'Título', 'Interessado', 'Produto/Serviço', 'Consultor', 'Etapa', 'Valor', 'Situação', 'Previsão'], ';');

            $query->orderBy('id')->chunk(200, function ($chunk) use ($out) {
                foreach ($chunk as $o) {
                    fputcsv($out, [
                        $o->id,
                        $o->titulo,
                        $o->interessado?->nome ?? '',
                        $o->produtoServico?->nome ?? '',
                        $o->consultor?->nome ?? '',
                        $o->etapaFunil?->nome ?? '',
                        number_format((float) $o->valor, 2, ',', '.'),
                        ucfirst($o->situacao ?? ''),
                        optional($o->data_previsao_fechamento)->format('d/m/Y') ?? '',
                    ], ';');
                }
            });
            fclose($out);
        }, 'oportunidades.csv', $headers);
    }
}
