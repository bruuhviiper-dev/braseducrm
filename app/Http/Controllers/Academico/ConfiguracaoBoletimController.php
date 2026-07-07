<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracaoBoletim;
use Illuminate\Http\Request;

class ConfiguracaoBoletimController extends Controller
{
    public function index()
    {
        $configuracoes = ConfiguracaoBoletim::orderBy('nome')->paginate(20);
        return view('academico.configuracoes-boletim.index', compact('configuracoes'));
    }

    public function create()
    {
        return view('academico.configuracoes-boletim.form');
    }

    public function store(Request $request)
    {
        ConfiguracaoBoletim::create($this->validateData($request));
        return redirect()->route('academico.configuracoes-boletim.index')->with('success', 'Configuração criada com sucesso.');
    }

    public function edit(ConfiguracaoBoletim $configuracoes_boletim)
    {
        $config = $configuracoes_boletim;
        return view('academico.configuracoes-boletim.form', compact('config'));
    }

    public function update(Request $request, ConfiguracaoBoletim $configuracoes_boletim)
    {
        $configuracoes_boletim->update($this->validateData($request));
        return redirect()->route('academico.configuracoes-boletim.index')->with('success', 'Configuração atualizada com sucesso.');
    }

    public function destroy(ConfiguracaoBoletim $configuracoes_boletim)
    {
        $configuracoes_boletim->delete();
        return redirect()->route('academico.configuracoes-boletim.index')->with('success', 'Configuração removida com sucesso.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'formula' => 'nullable|string',
            'media_aprovacao' => 'required|numeric|min:0',
            'frequencia_minima' => 'required|numeric|min:0|max:100',
            // Modelos de recuperação dos docs do EDUQ (Graduação / Pós / Cursos Livres)
            'modelo' => 'required|in:direto,recuperacao_media,recuperacao_substitui',
            'rec_min' => 'nullable|numeric|min:0',
            'rec_max' => 'nullable|numeric|min:0',
            'media_aprovacao_final' => 'nullable|numeric|min:0',
        ]);

        if ($data['modelo'] === 'direto') {
            $data['rec_min'] = 0;
            $data['rec_max'] = 5.99;
            $data['media_aprovacao_final'] = null;
        } else {
            $data['rec_min'] = $data['rec_min'] ?? 0;
            $data['rec_max'] = $data['rec_max'] ?? 5.99;
            $data['media_aprovacao_final'] = $data['media_aprovacao_final'] ?? 5;
        }

        return $data;
    }
}
