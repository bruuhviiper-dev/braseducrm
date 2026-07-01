<?php

namespace App\Http\Controllers\Geral;

use App\Http\Controllers\Controller;
use App\Models\CampanhaIndicacao;
use Illuminate\Http\Request;

class CampanhaIndicacaoController extends Controller
{
    public function index()
    {
        $campanhas = CampanhaIndicacao::withCount('indicacoes')->orderByDesc('id')->paginate(20);

        return view('geral.campanhas-indicacao.index', compact('campanhas'));
    }

    public function create()
    {
        return view('geral.campanhas-indicacao.form', ['campanha' => null]);
    }

    public function store(Request $request)
    {
        CampanhaIndicacao::create($this->validar($request));

        return redirect()->route('geral.campanhas-indicacao.index')->with('success', 'Campanha criada.');
    }

    public function edit(CampanhaIndicacao $campanha)
    {
        return view('geral.campanhas-indicacao.form', compact('campanha'));
    }

    public function update(Request $request, CampanhaIndicacao $campanha)
    {
        $campanha->update($this->validar($request));

        return redirect()->route('geral.campanhas-indicacao.index')->with('success', 'Campanha atualizada.');
    }

    public function destroy(CampanhaIndicacao $campanha)
    {
        $campanha->delete();

        return redirect()->route('geral.campanhas-indicacao.index')->with('success', 'Campanha removida.');
    }

    private function validar(Request $request): array
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'ativo' => 'boolean',
        ]);
        $data['ativo'] = $request->boolean('ativo');

        return $data;
    }
}
