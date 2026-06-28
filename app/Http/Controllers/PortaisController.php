<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracaoPortal;
use App\Models\PastaPortal;
use App\Models\PublicacaoPortal;
use Illuminate\Http\Request;

class PortaisController extends Controller
{
    public function index()
    {
        $stats = [
            'pastas' => PastaPortal::count(),
            'publicacoes' => PublicacaoPortal::count(),
        ];
        $config = ConfiguracaoPortal::current();
        return view('portais.index', compact('stats', 'config'));
    }

    public function configuracao()
    {
        $config = ConfiguracaoPortal::current();
        return view('portais.configuracao', compact('config'));
    }

    public function salvarConfiguracao(Request $request)
    {
        $data = $request->validate([
            'nome_portal' => 'required|string|max:255',
            'cor_primaria' => 'required|string|max:7',
            'mensagem_boas_vindas' => 'nullable|string',
        ]);
        $data['exibe_financeiro'] = $request->boolean('exibe_financeiro');
        $data['exibe_boletim'] = $request->boolean('exibe_boletim');
        $data['exibe_documentos'] = $request->boolean('exibe_documentos');
        $data['ativo'] = $request->boolean('ativo');

        ConfiguracaoPortal::current()->update($data);

        return redirect()->route('portais.index')->with('success', 'Configuração do portal salva com sucesso.');
    }
}
