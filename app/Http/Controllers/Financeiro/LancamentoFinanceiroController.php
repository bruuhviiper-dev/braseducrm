<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\LancamentoFinanceiro;
use App\Models\ContaBancaria;
use App\Models\PlanoContas;
use Illuminate\Http\Request;

class LancamentoFinanceiroController extends Controller
{
    public function index(Request $request)
    {
        $query = LancamentoFinanceiro::with(['contaBancaria', 'planoConta']);
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        $lancamentos = $query->orderByDesc('data_lancamento')->paginate(20)->withQueryString();

        $totalEntradas = LancamentoFinanceiro::where('tipo', 'entrada')->sum('valor');
        $totalSaidas = LancamentoFinanceiro::where('tipo', 'saida')->sum('valor');

        return view('financeiro.lancamentos.index', compact('lancamentos', 'totalEntradas', 'totalSaidas'));
    }

    public function create()
    {
        return view('financeiro.lancamentos.form', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['operador_id'] = auth()->id();
        LancamentoFinanceiro::create($data);
        return redirect()->route('financeiro.lancamentos.index')->with('success', 'Lançamento registrado com sucesso.');
    }

    public function edit(LancamentoFinanceiro $lancamento)
    {
        return view('financeiro.lancamentos.form', array_merge($this->formData(), compact('lancamento')));
    }

    public function update(Request $request, LancamentoFinanceiro $lancamento)
    {
        $data = $this->validateData($request);
        $lancamento->update($data);
        return redirect()->route('financeiro.lancamentos.index')->with('success', 'Lançamento atualizado com sucesso.');
    }

    public function destroy(LancamentoFinanceiro $lancamento)
    {
        $lancamento->delete();
        return redirect()->route('financeiro.lancamentos.index')->with('success', 'Lançamento removido com sucesso.');
    }

    private function formData(): array
    {
        return [
            'contas' => ContaBancaria::where('ativo', true)->orderBy('nome')->get(),
            // Regra de ouro do EDUQ: nunca há movimentação direta sobre conta sintética (S) — só analíticas (A)
            'planos' => PlanoContas::where('tipo', 'analitica')->orderBy('codigo')->get(),
        ];
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'conta_bancaria_id' => 'required|exists:contas_bancarias,id',
            'plano_conta_id' => 'nullable|exists:plano_contas,id,tipo,analitica',
            'tipo' => 'required|in:entrada,saida,transferencia',
            'valor' => 'required|numeric|min:0',
            'data_lancamento' => 'required|date',
            'descricao' => 'required|string|max:255',
            'documento_referencia' => 'nullable|string|max:255',
        ]);
    }
}
