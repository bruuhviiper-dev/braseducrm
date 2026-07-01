<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracaoNfse;
use Illuminate\Http\Request;

class ConfiguracaoNfseController extends Controller
{
    public function index()
    {
        $config = ConfiguracaoNfse::current();

        return view('financeiro.nfse.index', compact('config'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'ambiente' => 'required|in:' . implode(',', array_keys(ConfiguracaoNfse::AMBIENTES)),
            'regime_tributario' => 'nullable|in:' . implode(',', array_keys(ConfiguracaoNfse::REGIMES)),
            'inscricao_municipal' => 'nullable|string|max:255',
            'serie_rps' => 'nullable|string|max:255',
            'numero_rps_atual' => 'nullable|integer|min:1',
            'codigo_servico' => 'nullable|string|max:255',
            'aliquota_iss' => 'nullable|numeric|min:0|max:100',
            'discriminacao_padrao' => 'nullable|string',
        ]);
        $data['numero_rps_atual'] = $data['numero_rps_atual'] ?? 1;
        $data['aliquota_iss'] = $data['aliquota_iss'] ?? 0;
        $data['iss_retido'] = $request->boolean('iss_retido');
        $data['ativo'] = $request->boolean('ativo');

        ConfiguracaoNfse::current()->update($data);

        return redirect()->route('financeiro.nfse.index')->with('success', 'Configurações de NFS-e salvas.');
    }
}
