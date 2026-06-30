<?php

namespace App\Http\Controllers\Ead;

use App\Http\Controllers\Controller;
use App\Models\AvaliacaoEad;
use App\Models\CursoEad;
use Illuminate\Http\Request;

class AvaliacaoEadController extends Controller
{
    public function index()
    {
        $avaliacoes = AvaliacaoEad::with('cursoEad')->orderByDesc('id')->paginate(20);

        return view('ead.avaliacoes.index', compact('avaliacoes'));
    }

    public function create()
    {
        return view('ead.avaliacoes.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        AvaliacaoEad::create($this->validar($request));

        return redirect()->route('ead.avaliacoes.index')->with('success', 'Avaliação EAD criada com sucesso.');
    }

    public function edit(AvaliacaoEad $avaliacao)
    {
        return view('ead.avaliacoes.form', $this->dados($avaliacao));
    }

    public function update(Request $request, AvaliacaoEad $avaliacao)
    {
        $avaliacao->update($this->validar($request));

        return redirect()->route('ead.avaliacoes.index')->with('success', 'Avaliação EAD atualizada.');
    }

    public function destroy(AvaliacaoEad $avaliacao)
    {
        $avaliacao->delete();

        return redirect()->route('ead.avaliacoes.index')->with('success', 'Avaliação EAD removida.');
    }

    private function validar(Request $request): array
    {
        $data = $request->validate([
            'curso_ead_id' => 'required|exists:cursos_ead,id',
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'nota_minima' => 'required|numeric|min:0',
            'tentativas' => 'required|integer|min:1',
        ]);
        $data['ativo'] = $request->boolean('ativo', true);

        return $data;
    }

    private function dados(?AvaliacaoEad $avaliacao): array
    {
        return [
            'avaliacao' => $avaliacao,
            'cursos' => CursoEad::orderBy('nome')->get(),
        ];
    }
}
