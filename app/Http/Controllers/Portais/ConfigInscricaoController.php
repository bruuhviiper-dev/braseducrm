<?php

namespace App\Http\Controllers\Portais;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracaoPortalInscricao;
use Illuminate\Http\Request;

/** 92 — Configuração (Portal de Inscrição). */
class ConfigInscricaoController extends Controller
{
    public function index()
    {
        $config = ConfiguracaoPortalInscricao::current();
        return view('portais.config-inscricao', compact('config'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'nullable|string|max:255',
            'cor_primaria' => 'nullable|string|max:20',
            'texto_boas_vindas' => 'nullable|string',
        ]);
        $data['exigir_cpf'] = $request->boolean('exigir_cpf');
        $data['permitir_cupom'] = $request->boolean('permitir_cupom');

        ConfiguracaoPortalInscricao::current()->update($data);

        return redirect()->route('portais.config-inscricao.index')->with('success', 'Configuração do Portal de Inscrição salva.');
    }
}
