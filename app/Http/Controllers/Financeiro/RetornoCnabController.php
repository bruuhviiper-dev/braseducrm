<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\TituloReceber;
use Illuminate\Http\Request;

class RetornoCnabController extends Controller
{
    public function index()
    {
        return view('financeiro.retorno.index');
    }

    /**
     * Processa um arquivo de retorno CNAB 400. Para cada registro de detalhe (tipo 1)
     * com ocorrência de liquidação (06/09/17), localiza o título pelo nosso número e baixa.
     */
    public function processar(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|file|max:5120',
        ]);

        $conteudo = file_get_contents($request->file('arquivo')->getRealPath());
        $linhas = preg_split('/\r\n|\r|\n/', $conteudo);

        $baixados = 0;
        $naoEncontrados = 0;
        $ocorrenciasLiquidacao = ['06', '09', '17']; // liquidação normal / por conta / em cartório

        foreach ($linhas as $linha) {
            if (strlen($linha) < 150 || $linha[0] !== '1') {
                continue; // só registros de detalhe (tipo 1)
            }

            // Layout CNAB 400 (posições aproximadas do padrão CBR643):
            $nossoNumero = trim(substr($linha, 62, 11));        // 063-073
            $ocorrencia = substr($linha, 108, 2);               // 109-110
            $valorPago = (int) substr($linha, 253, 13) / 100;   // 254-266 valor pago

            if (!in_array($ocorrencia, $ocorrenciasLiquidacao)) {
                continue;
            }

            $titulo = TituloReceber::where('nosso_numero', ltrim($nossoNumero, '0'))
                ->orWhere('nosso_numero', $nossoNumero)
                ->where('situacao', 'aberto')
                ->first();

            if ($titulo) {
                $titulo->update([
                    'situacao' => 'pago',
                    'valor_pago' => $valorPago > 0 ? $valorPago : $titulo->valor_original,
                    'data_pagamento' => now(),
                    'forma_pagamento' => 'boleto',
                ]);
                $baixados++;
            } else {
                $naoEncontrados++;
            }
        }

        return back()->with('success', "Retorno processado: {$baixados} título(s) baixado(s), {$naoEncontrados} não localizado(s).");
    }
}
