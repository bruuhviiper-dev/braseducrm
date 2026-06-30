<?php

namespace App\Http\Controllers\Ead;

use App\Http\Controllers\Controller;
use App\Models\QuestaoAvulsa;
use App\Models\TagQuestao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestaoAvulsaController extends Controller
{
    public function index()
    {
        $questoes = QuestaoAvulsa::with('tag')->withCount('alternativas')->orderByDesc('id')->paginate(20);

        return view('ead.questoes.index', compact('questoes'));
    }

    public function create()
    {
        return view('ead.questoes.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        $questao = QuestaoAvulsa::create($data['questao']);
        $this->salvarAlternativas($questao, $data['alternativas']);

        return redirect()->route('ead.questoes.index')->with('success', 'Questão cadastrada com sucesso.');
    }

    public function edit(QuestaoAvulsa $questao)
    {
        return view('ead.questoes.form', $this->dados($questao->load('alternativas')));
    }

    public function update(Request $request, QuestaoAvulsa $questao)
    {
        $data = $this->validar($request);
        $questao->update($data['questao']);
        $this->salvarAlternativas($questao, $data['alternativas']);

        return redirect()->route('ead.questoes.index')->with('success', 'Questão atualizada.');
    }

    public function destroy(QuestaoAvulsa $questao)
    {
        $questao->delete();

        return redirect()->route('ead.questoes.index')->with('success', 'Questão removida.');
    }

    private function validar(Request $request): array
    {
        $v = $request->validate([
            'titulo' => 'nullable|string|max:255',
            'enunciado' => 'required|string',
            'tipo' => 'required|in:' . implode(',', array_keys(QuestaoAvulsa::TIPOS)),
            'peso' => 'nullable|numeric|min:0',
            'tag_questao_id' => 'nullable|exists:tags_questao,id',
            'explicacao' => 'nullable|string',
            'alternativas' => 'nullable|array',
            'alternativas.*.texto' => 'nullable|string',
            'corretas' => 'nullable|array',
        ]);

        // Mantém o índice ORIGINAL do DOM para casar com corretas[]
        $corretas = collect($request->input('corretas', []))->map(fn ($c) => (string) $c);
        $alternativas = collect($v['alternativas'] ?? [])
            ->filter(fn ($a) => isset($a['texto']) && trim($a['texto']) !== '')
            ->map(fn ($a, $idxOriginal) => [
                'texto' => $a['texto'],
                'correta' => $corretas->contains((string) $idxOriginal),
            ])
            ->values()->all();

        return [
            'questao' => [
                'ativo' => $request->boolean('ativo', true),
                'titulo' => $v['titulo'] ?? null,
                'enunciado' => $v['enunciado'],
                'tipo' => $v['tipo'],
                'peso' => $v['peso'] ?? null,
                'tag_questao_id' => $v['tag_questao_id'] ?? null,
                'explicacao' => $v['explicacao'] ?? null,
            ],
            'alternativas' => $alternativas,
        ];
    }

    private function salvarAlternativas(QuestaoAvulsa $questao, array $alternativas): void
    {
        DB::transaction(function () use ($questao, $alternativas) {
            $questao->alternativas()->delete();
            // Só múltipla escolha / V-F têm alternativas
            if (!in_array($questao->tipo, ['multipla_escolha', 'verdadeiro_falso'])) {
                return;
            }
            foreach (array_values($alternativas) as $i => $alt) {
                $questao->alternativas()->create([
                    'texto' => $alt['texto'],
                    'correta' => (bool) $alt['correta'],
                    'ordem' => $i,
                ]);
            }
        });
    }

    private function dados(?QuestaoAvulsa $questao): array
    {
        return [
            'questao' => $questao,
            'tags' => TagQuestao::orderBy('nome')->get(),
        ];
    }
}
