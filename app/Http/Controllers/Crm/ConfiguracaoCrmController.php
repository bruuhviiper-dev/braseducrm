<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracaoCrm;
use Illuminate\Http\Request;

class ConfiguracaoCrmController extends Controller
{
    public function index()
    {
        $config = ConfiguracaoCrm::current();
        return view('crm.configuracoes.index', compact('config'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'roleta_ativa' => 'boolean',
            'dias_perda_automatica' => 'nullable|integer|min:0',
            'rd_station_token' => 'nullable|string|max:255',
            'rd_station_url' => 'nullable|string|max:255',
        ]);
        $data['roleta_ativa'] = $request->boolean('roleta_ativa');

        $config = ConfiguracaoCrm::current();
        $config->update($data);

        return redirect()->route('crm.configuracoes.index')
            ->with('success', 'Configurações do CRM salvas com sucesso.');
    }
}
