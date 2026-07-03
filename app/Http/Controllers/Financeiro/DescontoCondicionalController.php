<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\DescontoCondicional;
use Illuminate\Http\Request;

class DescontoCondicionalController extends Controller
{
    public function index(Request $request)
    {
        $descontos = DescontoCondicional::withCount('itens')
            ->when($request->filled('search'), fn($q) => $q->where('nome', 'like', '%' . $request->search . '%'))
            ->orderBy('nome')->paginate(20)->withQueryString();
        return view('financeiro.descontos-condicionais.index', compact('descontos'));
    }

    public function create()
    {
        return view('financeiro.descontos-condicionais.form');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $desconto = DescontoCondicional::create([
            'nome' => $data['nome'],
            'tipo' => $data['tipo'],
            'aplicar' => $data['aplicar'],
            'valor' => 0,
            'ativo' => true,
        ]);
        $this->syncItens($desconto, $data['itens'] ?? []);
        return redirect()->route('financeiro.descontos-condicionais.index')->with('success', 'Desconto condicional criado com sucesso.');
    }

    public function edit(DescontoCondicional $desconto)
    {
        $desconto->load('itens');
        return view('financeiro.descontos-condicionais.form', compact('desconto'));
    }

    public function update(Request $request, DescontoCondicional $desconto)
    {
        $data = $this->validateData($request);
        $desconto->update([
            'nome' => $data['nome'],
            'tipo' => $data['tipo'],
            'aplicar' => $data['aplicar'],
        ]);
        $this->syncItens($desconto, $data['itens'] ?? []);
        return redirect()->route('financeiro.descontos-condicionais.index')->with('success', 'Desconto condicional atualizado com sucesso.');
    }

    public function destroy(DescontoCondicional $desconto)
    {
        $desconto->itens()->delete();
        $desconto->delete();
        return redirect()->route('financeiro.descontos-condicionais.index')->with('success', 'Desconto condicional removido com sucesso.');
    }

    private function syncItens(DescontoCondicional $desconto, array $itens): void
    {
        $ids = [];
        foreach ($itens as $item) {
            if (!empty($item['id'])) {
                $registro = $desconto->itens()->find($item['id']);
                if ($registro) {
                    $registro->update(['dias' => $item['dias'], 'valor' => $item['valor']]);
                    $ids[] = $registro->id;
                }
            } else {
                $ids[] = $desconto->itens()->create(['dias' => $item['dias'], 'valor' => $item['valor']])->id;
            }
        }
        $desconto->itens()->whereNotIn('id', $ids)->delete();
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:percentual,valor',
            'aplicar' => 'required|string|max:255',
            'itens' => 'nullable|array',
            'itens.*.id' => 'nullable|integer',
            'itens.*.dias' => 'required|integer|min:0',
            'itens.*.valor' => 'required|numeric|min:0',
        ]);
    }
}
