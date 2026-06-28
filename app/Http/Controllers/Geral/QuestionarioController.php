<?php

namespace App\Http\Controllers\Geral;

use App\Http\Controllers\Controller;
use App\Models\Questionario;
use App\Models\Questao;
use Illuminate\Http\Request;

class QuestionarioController extends Controller
{
    public function index()
    {
        $questionarios = Questionario::withCount('questoes')->orderBy('id', 'desc')->paginate(20);
        return view('geral.questionarios.index', compact('questionarios'));
    }

    public function create()
    {
        $questoes = Questao::where('ativo', true)->orderBy('id', 'desc')->get();
        return view('geral.questionarios.form', compact('questoes'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        $questionario = Questionario::create([
            'nome' => $validated['nome'],
            'descricao' => $validated['descricao'] ?? null,
            'tipo' => $validated['tipo'],
            'ativo' => $request->boolean('ativo', true),
        ]);
        $this->syncQuestoes($questionario, $validated['questoes'] ?? []);
        return redirect()->route('geral.questionarios.index')->with('success', 'Questionário criado com sucesso.');
    }

    public function edit(Questionario $questionario)
    {
        $questionario->load('questoes');
        $questoes = Questao::where('ativo', true)->orderBy('id', 'desc')->get();
        return view('geral.questionarios.form', compact('questionario', 'questoes'));
    }

    public function update(Request $request, Questionario $questionario)
    {
        $validated = $this->validateData($request);
        $questionario->update([
            'nome' => $validated['nome'],
            'descricao' => $validated['descricao'] ?? null,
            'tipo' => $validated['tipo'],
            'ativo' => $request->boolean('ativo'),
        ]);
        $this->syncQuestoes($questionario, $validated['questoes'] ?? []);
        return redirect()->route('geral.questionarios.index')->with('success', 'Questionário atualizado com sucesso.');
    }

    public function destroy(Questionario $questionario)
    {
        $questionario->questoes()->detach();
        $questionario->delete();
        return redirect()->route('geral.questionarios.index')->with('success', 'Questionário removido com sucesso.');
    }

    private function syncQuestoes(Questionario $questionario, array $questaoIds): void
    {
        $sync = [];
        foreach (array_values($questaoIds) as $i => $questaoId) {
            $sync[$questaoId] = ['ordem' => $i + 1, 'obrigatoria' => true];
        }
        $questionario->questoes()->sync($sync);
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo' => 'required|in:avaliacao_institucional,nps,feedback,avulso',
            'questoes' => 'nullable|array',
            'questoes.*' => 'exists:questoes,id',
        ]);
    }
}
