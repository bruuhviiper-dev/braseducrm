<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\TabelaAvaliacao;
use App\Models\TabelaAvaliacaoItem;
use App\Models\User;
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
        $operadores = User::where('ativo', true)->orderBy('nome')->get();
        return view('academico.tabelas-avaliacao.form', compact('operadores'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        $tabela = TabelaAvaliacao::create([
            'nome' => $validated['nome'],
            'nota_maxima' => $validated['nota_maxima'] ?? 10,
            'media_aprovacao' => $validated['media_aprovacao'] ?? 7,
            'formula' => $validated['formula'] ?? null,
            'visibilidade_operador' => $request->boolean('visibilidade_operador'),
            'operador_id' => $request->boolean('visibilidade_operador') ? ($validated['operador_id'] ?? null) : null,
            'descricao' => $validated['descricao'] ?? null,
        ]);
        $this->syncItens($tabela, $validated['itens'] ?? []);
        return redirect()->route('academico.tabelas-avaliacao.index')->with('success', 'Tabela de avaliação criada com sucesso.');
    }

    public function edit(TabelaAvaliacao $tabelas_avaliacao)
    {
        $tabela = $tabelas_avaliacao->load('itens');
        $operadores = User::where('ativo', true)->orderBy('nome')->get();
        return view('academico.tabelas-avaliacao.form', compact('tabela', 'operadores'));
    }

    public function update(Request $request, TabelaAvaliacao $tabelas_avaliacao)
    {
        $validated = $this->validateData($request);
        $tabelas_avaliacao->update([
            'nome' => $validated['nome'],
            'nota_maxima' => $validated['nota_maxima'] ?? 10,
            'media_aprovacao' => $validated['media_aprovacao'] ?? 7,
            'formula' => $validated['formula'] ?? null,
            'visibilidade_operador' => $request->boolean('visibilidade_operador'),
            'operador_id' => $request->boolean('visibilidade_operador') ? ($validated['operador_id'] ?? null) : null,
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
            $attrs = [
                'nome' => $item['nome'],
                'peso' => $item['peso'],
                'ordem' => $i + 1,
                // EDUQ: o item marcado como REC é a nota de recuperação (fica fora da Média Parcial)
                'recuperacao' => !empty($item['recuperacao']),
            ];
            if (!empty($item['id'])) {
                $registro = TabelaAvaliacaoItem::where('id', $item['id'])->where('tabela_avaliacao_id', $tabela->id)->first();
                if ($registro) {
                    $registro->update($attrs);
                    $existingIds[] = $registro->id;
                }
            } else {
                $novo = $tabela->itens()->create($attrs);
                $existingIds[] = $novo->id;
            }
        }
        $tabela->itens()->whereNotIn('id', $existingIds)->delete();
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nome' => 'required|string|max:255',
            'nota_maxima' => 'nullable|numeric|min:1',
            'media_aprovacao' => 'nullable|numeric|min:0',
            'formula' => 'nullable|string|max:250',
            'visibilidade_operador' => 'nullable|boolean',
            'operador_id' => 'nullable|exists:users,id',
            'descricao' => 'nullable|string',
            'itens' => 'nullable|array',
            'itens.*.id' => 'nullable|integer',
            'itens.*.nome' => 'required|string|max:255',
            'itens.*.peso' => 'required|numeric|min:0',
            'itens.*.recuperacao' => 'nullable|boolean',
        ]);
    }
}
