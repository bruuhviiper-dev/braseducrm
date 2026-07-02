<?php

namespace App\Http\Controllers\Estoque;

use App\Http\Controllers\Controller;
use App\Models\MovimentacaoEstoque;
use App\Models\ProdutoEstoque;
use App\Models\Deposito;
use Illuminate\Http\Request;

class MovimentacaoEstoqueController extends Controller
{
    public function index()
    {
        $movimentacoes = MovimentacaoEstoque::with('produtoEstoque', 'deposito')->orderByDesc('created_at')->paginate(20);
        return view('estoque.movimentacoes.index', compact('movimentacoes'));
    }

    public function create()
    {
        $produtos = ProdutoEstoque::orderBy('nome')->get();
        $depositos = Deposito::orderBy('nome')->get();
        return view('estoque.movimentacoes.form', compact('produtos', 'depositos'));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);
        $data['operador_id'] = auth()->id();
        MovimentacaoEstoque::create($data);

        $produto = ProdutoEstoque::find($data['produto_estoque_id']);
        if ($data['tipo'] === 'entrada') {
            $produto->increment('estoque_atual', $data['quantidade']);
        } elseif ($data['tipo'] === 'saida') {
            $produto->decrement('estoque_atual', $data['quantidade']);
        }
        // transferência não altera o estoque total (apenas move entre depósitos)

        return redirect()->route('estoque.movimentacoes.index')->with('success', 'Movimentação registrada com sucesso.');
    }

    private function validar(Request $request): array
    {
        $v = $request->validate([
            'produto_estoque_id' => 'required|exists:produtos_estoque,id',
            'tipo' => 'required|in:entrada,saida,transferencia',
            'data_movimentacao' => 'nullable|date',
            'deposito_id' => 'nullable|exists:depositos,id',
            'deposito_origem_id' => 'nullable|exists:depositos,id',
            'deposito_destino_id' => 'nullable|exists:depositos,id',
            'quantidade' => 'required|numeric|min:0.01',
            'valor_unitario' => 'nullable|numeric|min:0',
            'motivo' => 'nullable|string|max:500',
        ]);

        // Transferência exige origem e destino; entrada/saída exigem depósito
        if ($v['tipo'] === 'transferencia') {
            $request->validate([
                'deposito_origem_id' => 'required|exists:depositos,id',
                'deposito_destino_id' => 'required|exists:depositos,id|different:deposito_origem_id',
            ]);
        } else {
            $request->validate(['deposito_id' => 'required|exists:depositos,id']);
        }

        return $v + ['data_movimentacao' => $v['data_movimentacao'] ?? now()->toDateString()];
    }

    public function edit(MovimentacaoEstoque $movimentacao)
    {
        $produtos = ProdutoEstoque::orderBy('nome')->get();
        $depositos = Deposito::orderBy('nome')->get();
        return view('estoque.movimentacoes.form', compact('movimentacao', 'produtos', 'depositos'));
    }

    public function update(Request $request, MovimentacaoEstoque $movimentacao)
    {
        $data = $request->validate([
            'produto_estoque_id' => 'required|exists:produtos_estoque,id',
            'deposito_id' => 'required|exists:depositos,id',
            'tipo' => 'required|in:entrada,saida,transferencia',
            'quantidade' => 'required|numeric|min:0.01',
            'valor_unitario' => 'nullable|numeric|min:0',
            'motivo' => 'nullable|string|max:500',
        ]);

        $movimentacao->update($data);
        return redirect()->route('estoque.movimentacoes.index')->with('success', 'Movimentacao atualizada com sucesso.');
    }

    public function destroy(MovimentacaoEstoque $movimentacao)
    {
        $movimentacao->delete();
        return redirect()->route('estoque.movimentacoes.index')->with('success', 'Movimentacao removida com sucesso.');
    }
}
