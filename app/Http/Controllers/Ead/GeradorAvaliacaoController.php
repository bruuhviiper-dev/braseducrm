<?php

namespace App\Http\Controllers\Ead;

use App\Http\Controllers\Controller;
use App\Models\GeradorAvaliacao;
use App\Models\QuestaoAvulsa;
use App\Models\TagQuestao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeradorAvaliacaoController extends Controller
{
    public function index()
    {
        $geradores = GeradorAvaliacao::withCount('parametros')->orderByDesc('id')->paginate(20);

        return view('ead.geradores.index', compact('geradores'));
    }

    public function create()
    {
        return view('ead.geradores.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        $gerador = GeradorAvaliacao::create(['descricao' => $data['descricao']]);
        $this->salvarParametros($gerador, $data['parametros']);

        return redirect()->route('ead.geradores.index')->with('success', 'Gerador de avaliação criado.');
    }

    public function edit(GeradorAvaliacao $geradore)
    {
        return view('ead.geradores.form', $this->dados($geradore->load('parametros')));
    }

    public function update(Request $request, GeradorAvaliacao $geradore)
    {
        $data = $this->validar($request);
        $geradore->update(['descricao' => $data['descricao']]);
        $this->salvarParametros($geradore, $data['parametros']);

        return redirect()->route('ead.geradores.index')->with('success', 'Gerador atualizado.');
    }

    public function destroy(GeradorAvaliacao $geradore)
    {
        $geradore->delete();

        return redirect()->route('ead.geradores.index')->with('success', 'Gerador removido.');
    }

    private function validar(Request $request): array
    {
        $v = $request->validate([
            'descricao' => 'required|string|max:255',
            'parametros' => 'nullable|array',
            'parametros.*.tag_questao_id' => 'nullable|exists:tags_questao,id',
            'parametros.*.quantidade' => 'nullable|integer|min:1',
        ]);

        return [
            'descricao' => $v['descricao'],
            'parametros' => collect($v['parametros'] ?? [])
                ->filter(fn ($p) => !empty($p['quantidade']))
                ->values()->all(),
        ];
    }

    private function salvarParametros(GeradorAvaliacao $gerador, array $parametros): void
    {
        DB::transaction(function () use ($gerador, $parametros) {
            $gerador->parametros()->delete();
            foreach ($parametros as $p) {
                $gerador->parametros()->create([
                    'tag_questao_id' => $p['tag_questao_id'] ?? null,
                    'quantidade' => $p['quantidade'],
                ]);
            }
        });
    }

    private function dados(?GeradorAvaliacao $gerador): array
    {
        return [
            'gerador' => $gerador,
            'tags' => TagQuestao::orderBy('nome')->get(),
            'totalQuestoes' => QuestaoAvulsa::where('ativo', true)->count(),
        ];
    }
}
