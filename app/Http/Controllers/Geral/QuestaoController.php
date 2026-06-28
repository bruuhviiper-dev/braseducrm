<?php

namespace App\Http\Controllers\Geral;

use App\Http\Controllers\Controller;
use App\Models\Questao;
use App\Models\Opcao;
use App\Models\TagQuestao;
use Illuminate\Http\Request;

class QuestaoController extends Controller
{
    public function index()
    {
        $questoes = Questao::with('tag')->withCount('opcoes')->orderBy('id', 'desc')->paginate(20);
        return view('geral.questoes.index', compact('questoes'));
    }

    public function create()
    {
        $tags = TagQuestao::orderBy('nome')->get();
        return view('geral.questoes.form', compact('tags'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        $questao = Questao::create([
            'enunciado' => $validated['enunciado'],
            'tipo' => $validated['tipo'],
            'tag_questao_id' => $validated['tag_questao_id'] ?? null,
            'ativo' => $request->boolean('ativo', true),
        ]);
        $this->syncOpcoes($questao, $validated['opcoes'] ?? []);
        return redirect()->route('geral.questoes.index')->with('success', 'Questão criada com sucesso.');
    }

    public function edit(Questao $questao)
    {
        $questao->load('opcoes');
        $tags = TagQuestao::orderBy('nome')->get();
        return view('geral.questoes.form', compact('questao', 'tags'));
    }

    public function update(Request $request, Questao $questao)
    {
        $validated = $this->validateData($request);
        $questao->update([
            'enunciado' => $validated['enunciado'],
            'tipo' => $validated['tipo'],
            'tag_questao_id' => $validated['tag_questao_id'] ?? null,
            'ativo' => $request->boolean('ativo'),
        ]);
        $this->syncOpcoes($questao, $validated['opcoes'] ?? []);
        return redirect()->route('geral.questoes.index')->with('success', 'Questão atualizada com sucesso.');
    }

    public function destroy(Questao $questao)
    {
        $questao->opcoes()->delete();
        $questao->delete();
        return redirect()->route('geral.questoes.index')->with('success', 'Questão removida com sucesso.');
    }

    private function syncOpcoes(Questao $questao, array $opcoes): void
    {
        $questao->opcoes()->delete();
        if (!in_array($questao->tipo, ['multipla_escolha', 'escala', 'verdadeiro_falso'])) {
            return;
        }
        foreach ($opcoes as $i => $op) {
            if (($op['texto'] ?? '') === '') {
                continue;
            }
            $questao->opcoes()->create([
                'texto' => $op['texto'],
                'valor' => $op['valor'] ?? null,
                'ordem' => $i + 1,
            ]);
        }
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'enunciado' => 'required|string',
            'tipo' => 'required|in:multipla_escolha,dissertativa,escala,verdadeiro_falso',
            'tag_questao_id' => 'nullable|exists:tags_questao,id',
            'opcoes' => 'nullable|array',
            'opcoes.*.texto' => 'nullable|string|max:255',
            'opcoes.*.valor' => 'nullable|numeric',
        ]);
    }
}
