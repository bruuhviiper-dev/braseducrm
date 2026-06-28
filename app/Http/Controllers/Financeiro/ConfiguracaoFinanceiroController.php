<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracaoFinanceiro;
use Illuminate\Http\Request;

class ConfiguracaoFinanceiroController extends Controller
{
    public function index()
    {
        $config = ConfiguracaoFinanceiro::current();
        return view('financeiro.configuracao.index', compact('config'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'multa_atraso' => 'required|numeric|min:0',
            'juros_dia' => 'required|numeric|min:0',
        ]);
        $data['boleto_automatico'] = $request->boolean('boleto_automatico');
        $data['cartao_recorrente'] = $request->boolean('cartao_recorrente');

        ConfiguracaoFinanceiro::current()->update($data);
        return redirect()->route('financeiro.configuracao.index')->with('success', 'Configuração do Financeiro salva.');
    }
}
