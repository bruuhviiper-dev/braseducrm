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
        return $request->validate([
            'nome' => 'required|string|max:255',
            'formula' => 'nullable|string',
            'media_aprovacao' => 'required|numeric|min:0',
            'frequencia_minima' => 'required|numeric|min:0|max:100',
        ]);
    }
}
