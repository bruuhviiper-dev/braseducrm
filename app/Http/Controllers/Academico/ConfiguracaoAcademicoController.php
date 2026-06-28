<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracaoAcademico;
use Illuminate\Http\Request;

class ConfiguracaoAcademicoController extends Controller
{
    public function index()
    {
        $config = ConfiguracaoAcademico::current();
        return view('academico.configuracao.index', compact('config'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'email_matricula_template' => 'nullable|string',
        ]);
        $data['assinatura_eletronica'] = $request->boolean('assinatura_eletronica');
        $data['envio_email_matricula'] = $request->boolean('envio_email_matricula');
        $data['aniversariante_automatico'] = $request->boolean('aniversariante_automatico');

        ConfiguracaoAcademico::current()->update($data);
        return redirect()->route('academico.configuracao.index')->with('success', 'Configuração do Acadêmico salva.');
    }
}
