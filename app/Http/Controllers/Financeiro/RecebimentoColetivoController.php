<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\ContaBancaria;
use App\Models\TituloReceber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** 259 - Recebimento Coletivo (Bancário): baixa em lote de títulos em aberto. */
class RecebimentoColetivoController extends Controller
{
    public function index(Request $request)
    {
        $query = TituloReceber::with(['pessoa', 'categoriaReceber'])
            ->where('situacao', 'aberto')
            ->orderBy('data_vencimento');

        if ($request->filled('de')) {
            $query->where('data_vencimento', '>=', $request->de);
        }
        if ($request->filled('ate')) {
            $query->where('data_vencimento', '<=', $request->ate);
        }
        if ($search = $request->get('search')) {
            $query->whereHas('pessoa', fn ($q) => $q->where('nome', 'like', "%{$search}%"));
        }

        $titulos = $query->limit(200)->get();
        $contas = ContaBancaria::where('ativo', true)->orderBy('nome')->get();

        return view('financeiro.recebimento-coletivo.index', compact('titulos', 'contas'));
    }

    public function processar(Request $request)
    {
        $data = $request->validate([
            'titulos' => 'required|array|min:1',
            'titulos.*' => 'exists:titulos_receber,id',
            'data_pagamento' => 'required|date',
            'conta_bancaria_id' => 'nullable|exists:contas_bancarias,id',
        ], [
            'titulos.required' => 'Selecione ao menos um título para receber.',
        ]);

        $count = 0;
        DB::transaction(function () use ($data, &$count) {
            foreach ($data['titulos'] as $id) {
                $t = TituloReceber::find($id);
                if (!$t || $t->situacao !== 'aberto') {
                    continue;
                }
                $t->update([
                    'situacao' => 'pago',
                    'valor_pago' => $t->valor_original - ($t->valor_desconto ?? 0),
                    'data_pagamento' => $data['data_pagamento'],
                    'conta_bancaria_id' => $data['conta_bancaria_id'] ?? $t->conta_bancaria_id,
                ]);
                $count++;
            }
        });

        return redirect()->route('financeiro.recebimento-coletivo.index')
            ->with('success', "{$count} título(s) recebido(s) em lote.");
    }
}
