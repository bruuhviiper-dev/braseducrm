<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\Banco;
use App\Models\CartaoEmpresarial;
use Illuminate\Http\Request;

class CartaoEmpresarialController extends Controller
{
    public function index()
    {
        $cartoes = CartaoEmpresarial::with('banco')->orderBy('nome')->paginate(20);

        return view('financeiro.cartoes-empresariais.index', compact('cartoes'));
    }

    public function create()
    {
        return view('financeiro.cartoes-empresariais.form', ['cartao' => null, 'bancos' => $this->bancos()]);
    }

    public function store(Request $request)
    {
        CartaoEmpresarial::create($this->validar($request));

        return redirect()->route('financeiro.cartoes-empresariais.index')->with('success', 'Cartão empresarial criado.');
    }

    public function edit(CartaoEmpresarial $cartoes_empresariai)
    {
        return view('financeiro.cartoes-empresariais.form', ['cartao' => $cartoes_empresariai, 'bancos' => $this->bancos()]);
    }

    public function update(Request $request, CartaoEmpresarial $cartoes_empresariai)
    {
        $cartoes_empresariai->update($this->validar($request));

        return redirect()->route('financeiro.cartoes-empresariais.index')->with('success', 'Cartão atualizado.');
    }

    public function destroy(CartaoEmpresarial $cartoes_empresariai)
    {
        $cartoes_empresariai->delete();

        return redirect()->route('financeiro.cartoes-empresariais.index')->with('success', 'Cartão removido.');
    }

    private function validar(Request $request): array
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'bandeira' => 'nullable|string|max:255',
            'ultimos_digitos' => 'nullable|string|max:4',
            'banco_id' => 'nullable|exists:bancos,id',
            'limite' => 'nullable|numeric|min:0',
            'dia_fechamento' => 'nullable|integer|min:1|max:31',
            'dia_vencimento' => 'nullable|integer|min:1|max:31',
            'ativo' => 'boolean',
        ]);
        $data['limite'] = $data['limite'] ?? 0;
        $data['ativo'] = $request->boolean('ativo');

        return $data;
    }

    private function bancos()
    {
        return Banco::where('ativo', true)->orderBy('nome')->get();
    }
}
