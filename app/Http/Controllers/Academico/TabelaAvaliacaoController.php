<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\TabelaAvaliacao;
use App\Models\TabelaAvaliacaoItem;
use Illuminate\Http\Request;

class TabelaAvaliacaoController extends Controller
{
    public function index()
    {
        $tabelas = TabelaAvaliacao::withCount('itens')->orderBy('nome')->paginate(20);
        return view('academico.tabelas-avaliacao.index', compact('tabelas'));
    }

    public function create()
    {
        return view('academico.tabelas-avaliacao.form');
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        $tabela = TabelaAvaliacao::create([
            'nome' => $validated['nome'],
            'nota_maxima' => $validated['nota_maxima'],
            'media_aprovacao' => $validated['media_aprovacao'],
            'descricao' => $validated['descricao'] ?? null,
        ]);
        $this->syncItens($tabela, $validated['itens'] ?? []);
        return redirect()->route('academico.tabelas-avaliacao.index')->with('success', 'Tabela de avaliação criada com sucesso.');
    }

    public function edit(TabelaAvaliacao $tabelas_avaliacao)
    {
        $tabela = $tabelas_avaliacao->load('itens');
        return view('academico.tabelas-avaliacao.form', compact('tabela'));
    }

    public function update(Request $request, TabelaAvaliacao $tabelas_avaliacao)
    {
        $validated = $this->validateData($request);
        $tabelas_avaliacao->update([
            'nome' => $validated['nome'],
            'nota_maxima' => $validated['nota_maxima'],
            'media_aprovacao' => $validated['media_aprovacao'],
            'descricao' => $validated['descricao'] ?? null,
        ]);
        $this->syncItens($tabelas_avaliacao, $validated['itens'] ?? []);
        return redirect()->route('academico.tabelas-avaliacao.index')->with('success', 'Tabela de avaliação atualizada com sucesso.');
    }

    public function destroy(TabelaAvaliacao $tabelas_avaliacao)
    {
        $tabelas_avaliacao->itens()->delete();
        $tabelas_avaliacao->delete();
        return redirect()->route('academico.tabelas-avaliacao.index')->with('success', 'Tabela removida com sucesso.');
    }

    private function syncItens(TabelaAvaliacao $tabela, array $itens): void
    {
        $existingIds = [];
        foreach ($itens as $i => $item) {
            if (!empty($item['id'])) {
                $registro = TabelaAvaliacaoItem::where('id', $item['id'])->where('tabela_avaliacao_id', $tabela->id)->first();
                if ($registro) {
                    $registro->update(['nome' => $item['nome'], 'peso' => $item['peso'], 'ordem' => $i + 1]);
                    $existingIds[] = $registro->id;
                }
            } else {
                $novo = $tabela->itens()->create(['nome' => $item['nome'], 'peso' => $item['peso'], 'ordem' => $i + 1]);
                $existingIds[] = $novo->id;
            }
        }
        $tabela->itens()->whereNotIn('id', $existingIds)->delete();
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nome' => 'required|string|max:255',
            'nota_maxima' => 'required|numeric|min:1',
            'media_aprovacao' => 'required|numeric|min:0',
            'descricao' => 'nullable|string',
            'itens' => 'nullable|array',
            'itens.*.id' => 'nullable|integer',
            'itens.*.nome' => 'required|string|max:255',
            'itens.*.peso' => 'required|numeric|min:0',
        ]);
    }
}
