<?php

namespace App\Http\Controllers\Comunicacao;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracaoComunicacao;
use Illuminate\Http\Request;

class ConfiguracaoComunicacaoController extends Controller
{
    public function index()
    {
        $config = ConfiguracaoComunicacao::current();
        return view('comunicacao.configuracao.index', compact('config'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'remetente_nome' => 'nullable|string|max:255',
            'remetente_email' => 'nullable|email|max:255',
            'canal_padrao' => 'required|in:email,sms,whatsapp',
            'assinatura' => 'nullable|string',
            'dias_aviso_vencimento' => 'required|integer|min:0',
        ]);
        $data['enviar_aviso_vencimento'] = $request->boolean('enviar_aviso_vencimento');
        $data['enviar_aviso_cobranca'] = $request->boolean('enviar_aviso_cobranca');

        ConfiguracaoComunicacao::current()->update($data);
        return redirect()->route('comunicacao.configuracao.index')->with('success', 'Configuração da Comunicação salva.');
    }
}
