<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\PlanoContas;
use Illuminate\Http\Request;

class PlanoContasController extends Controller
{
    public function index()
    {
        $contas = PlanoContas::with('filhosRecursivos')
            ->whereNull('pai_id')
            ->orderBy('codigo')
            ->get();

        return view('financeiro.plano-contas.index', compact('contas'));
    }

    public function create()
    {
        $pais = PlanoContas::where('tipo', 'sintetica')
            ->orderBy('codigo')
            ->get();

        return view('financeiro.plano-contas.form', compact('pais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:20|unique:plano_contas,codigo',
            'nome' => 'required|string|max:255',
            'pai_id' => 'nullable|exists:plano_contas,id',
            'tipo' => 'required|in:sintetica,analitica',
            'natureza' => 'required|in:receita,despesa',
            'mascara_filhos' => 'nullable|string|max:50',
            'tesouraria' => 'nullable|boolean',
            'identificador_integracao' => 'nullable|string|max:255',
        ]);

        $data = $request->all();

        if ($request->input('pai_id')) {
            $pai = PlanoContas::find($request->input('pai_id'));
            $data['nivel'] = $pai->nivel + 1;
        } else {
            $data['nivel'] = 1;
        }

        $data['ativo'] = $request->has('ativo');

        PlanoContas::create($data);

        return redirect()->route('financeiro.plano-contas.index')
            ->with('success', 'Conta cadastrada com sucesso.');
    }

    public function edit(PlanoContas $plano_conta)
    {
        $conta = $plano_conta;
        $pais = PlanoContas::where('tipo', 'sintetica')
            ->where('id', '!=', $conta->id)
            ->orderBy('codigo')
            ->get();

        return view('financeiro.plano-contas.form', compact('conta', 'pais'));
    }

    public function update(Request $request, PlanoContas $plano_conta)
    {
        $conta = $plano_conta;

        $request->validate([
            'codigo' => 'required|string|max:20|unique:plano_contas,codigo,' . $conta->id,
            'nome' => 'required|string|max:255',
            'pai_id' => 'nullable|exists:plano_contas,id',
            'tipo' => 'required|in:sintetica,analitica',
            'natureza' => 'required|in:receita,despesa',
            'mascara_filhos' => 'nullable|string|max:50',
            'tesouraria' => 'nullable|boolean',
            'identificador_integracao' => 'nullable|string|max:255',
        ]);

        $data = $request->all();

        if ($request->input('pai_id')) {
            $pai = PlanoContas::find($request->input('pai_id'));
            $data['nivel'] = $pai->nivel + 1;
        } else {
            $data['nivel'] = 1;
        }

        $data['ativo'] = $request->has('ativo');

        $conta->update($data);

        return redirect()->route('financeiro.plano-contas.index')
            ->with('success', 'Conta atualizada com sucesso.');
    }

    public function destroy(PlanoContas $plano_conta)
    {
        if ($plano_conta->filhos()->count() > 0) {
            return redirect()->route('financeiro.plano-contas.index')
                ->with('error', 'Nao e possivel remover uma conta que possui subcontas.');
        }

        $plano_conta->delete();

        return redirect()->route('financeiro.plano-contas.index')
            ->with('success', 'Conta removida com sucesso.');
    }
}
