<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\CategoriaReceber;
use App\Models\TituloReceber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** 175 - Atualização de Parcelas pelo Índice: reajusta em lote títulos em aberto por percentual. */
class AtualizacaoIndiceController extends Controller
{
    public function index(Request $request)
    {
        $categorias = CategoriaReceber::orderBy('nome')->get();
        $previa = null;

        if ($request->filled('percentual')) {
            $query = $this->filtrar($request);
            $previa = [
                'quantidade' => (clone $query)->count(),
                'total_atual' => (float) (clone $query)->sum('valor_original'),
                'percentual' => (float) $request->percentual,
            ];
            $previa['total_novo'] = $previa['total_atual'] * (1 + $previa['percentual'] / 100);
        }

        return view('financeiro.atualizacao-indice.index', compact('categorias', 'previa'));
    }

    public function aplicar(Request $request)
    {
        $data = $request->validate([
            'percentual' => 'required|numeric|min:-100|max:1000',
            'categoria_receber_id' => 'nullable|exists:categorias_receber,id',
            'vencimento_de' => 'nullable|date',
        ]);

        $query = $this->filtrar($request);
        $fator = 1 + (float) $data['percentual'] / 100;
        $count = 0;

        DB::transaction(function () use ($query, $fator, &$count) {
            foreach ($query->get() as $t) {
                $t->update(['valor_original' => round($t->valor_original * $fator, 2)]);
                $count++;
            }
        });

        return redirect()->route('financeiro.atualizacao-indice.index')
            ->with('success', "Índice de " . number_format((float) $data['percentual'], 2, ',', '.') . "% aplicado em {$count} parcela(s) em aberto.");
    }

    private function filtrar(Request $request)
    {
        $query = TituloReceber::where('situacao', 'aberto');
        if ($request->filled('categoria_receber_id')) {
            $query->where('categoria_receber_id', $request->categoria_receber_id);
        }
        if ($request->filled('vencimento_de')) {
            $query->where('data_vencimento', '>=', $request->vencimento_de);
        }
        return $query;
    }
}
