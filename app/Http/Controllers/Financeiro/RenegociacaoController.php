<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\Renegociacao;
use App\Models\TituloReceber;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class RenegociacaoController extends Controller
{
    public function index()
    {
        $renegociacoes = Renegociacao::with('pessoa')->orderByDesc('data_renegociacao')->paginate(20);
        return view('financeiro.renegociacoes.index', compact('renegociacoes'));
    }

    public function create(Request $request)
    {
        $pessoas = Pessoa::orderBy('nome')->get();
        $titulos = collect();
        $pessoaSelecionada = null;

        if ($request->filled('pessoa_id')) {
            $pessoaSelecionada = $request->pessoa_id;
            $titulos = TituloReceber::where('pessoa_id', $pessoaSelecionada)
                ->where('situacao', 'aberto')
                ->orderBy('data_vencimento')->get();
        }

        return view('financeiro.renegociacoes.form', compact('pessoas', 'titulos', 'pessoaSelecionada'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pessoa_id' => 'required|exists:pessoas,id',
            'titulos' => 'required|array|min:1',
            'titulos.*' => 'exists:titulos_receber,id',
            'valor_total_renegociado' => 'required|numeric|min:0.01',
            'numero_parcelas' => 'required|integer|min:1|max:60',
            'primeiro_vencimento' => 'required|date',
            'observacoes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($data) {
            $titulos = TituloReceber::whereIn('id', $data['titulos'])
                ->where('pessoa_id', $data['pessoa_id'])->where('situacao', 'aberto')->get();

            $valorOriginal = (float) $titulos->sum('valor_original');

            // Marca os títulos originais como renegociados
            TituloReceber::whereIn('id', $titulos->pluck('id'))->update(['situacao' => 'renegociado']);

            // Cria as novas parcelas
            $valorParcela = round($data['valor_total_renegociado'] / $data['numero_parcelas'], 2);
            $venc = Carbon::parse($data['primeiro_vencimento']);
            for ($i = 1; $i <= $data['numero_parcelas']; $i++) {
                TituloReceber::create([
                    'pessoa_id' => $data['pessoa_id'],
                    'numero_documento' => 'RENEG-' . now()->format('YmdHis') . '-' . $i,
                    'valor_original' => $valorParcela,
                    'data_emissao' => now(),
                    'data_vencimento' => $venc->copy()->addMonths($i - 1),
                    'situacao' => 'aberto',
                    'observacoes' => 'Parcela ' . $i . '/' . $data['numero_parcelas'] . ' de renegociação',
                ]);
            }

            Renegociacao::create([
                'pessoa_id' => $data['pessoa_id'],
                'titulos_originais' => $titulos->pluck('id')->toArray(),
                'valor_total_original' => $valorOriginal,
                'valor_total_renegociado' => $data['valor_total_renegociado'],
                'numero_parcelas' => $data['numero_parcelas'],
                'data_renegociacao' => now(),
                'observacoes' => $data['observacoes'] ?? null,
                'operador_id' => auth()->id(),
            ]);
        });

        return redirect()->route('financeiro.renegociacoes.index')->with('success', 'Renegociação realizada: títulos originais baixados e novas parcelas geradas.');
    }
}
