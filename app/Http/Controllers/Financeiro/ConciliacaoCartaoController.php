<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\ContratoCartao;
use App\Models\RecebimentoCartao;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ConciliacaoCartaoController extends Controller
{
    public function index(Request $request)
    {
        $query = RecebimentoCartao::with('contrato');
        if ($request->filled('conciliado')) {
            $query->where('conciliado', $request->conciliado === '1');
        }
        if ($request->filled('contrato_cartao_id')) {
            $query->where('contrato_cartao_id', $request->contrato_cartao_id);
        }
        $recebimentos = $query->orderByDesc('data_venda')->paginate(20)->withQueryString();

        $totais = [
            'bruto' => RecebimentoCartao::sum('valor_bruto'),
            'liquido' => RecebimentoCartao::sum('valor_liquido'),
            'pendente' => RecebimentoCartao::where('conciliado', false)->sum('valor_liquido'),
        ];

        $contratos = ContratoCartao::orderBy('operadora')->get();

        return view('financeiro.conciliacao-cartao.index', compact('recebimentos', 'totais', 'contratos'));
    }

    public function create()
    {
        return view('financeiro.conciliacao-cartao.form', ['contratos' => ContratoCartao::where('ativo', true)->orderBy('operadora')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'contrato_cartao_id' => 'required|exists:contratos_cartao,id',
            'data_venda' => 'required|date',
            'bandeira' => 'nullable|string|max:255',
            'modalidade' => 'required|in:' . implode(',', array_keys(RecebimentoCartao::MODALIDADES)),
            'parcelas' => 'nullable|integer|min:1|max:36',
            'valor_bruto' => 'required|numeric|min:0',
        ]);

        $contrato = ContratoCartao::findOrFail($data['contrato_cartao_id']);
        $taxa = $contrato->taxaPara($data['modalidade']);
        $bruto = (float) $data['valor_bruto'];
        $liquido = round($bruto * (1 - $taxa / 100), 2);
        $previsao = Carbon::parse($data['data_venda'])->addDays($contrato->prazo_recebimento_dias);

        RecebimentoCartao::create([
            'contrato_cartao_id' => $contrato->id,
            'data_venda' => $data['data_venda'],
            'bandeira' => $data['bandeira'] ?? null,
            'modalidade' => $data['modalidade'],
            'parcelas' => $data['modalidade'] === 'credito_parcelado' ? ($data['parcelas'] ?? 1) : 1,
            'valor_bruto' => $bruto,
            'taxa_aplicada' => $taxa,
            'valor_liquido' => $liquido,
            'previsao_recebimento' => $previsao,
            'conciliado' => false,
        ]);

        return redirect()->route('financeiro.conciliacao-cartao.index')
            ->with('success', 'Recebimento lançado. Taxa ' . number_format($taxa, 2, ',', '.') . '% → líquido R$ ' . number_format($liquido, 2, ',', '.') . '.');
    }

    /** Marca/desmarca a conciliação (recebimento confirmado na conta). */
    public function conciliar(RecebimentoCartao $recebimento)
    {
        $novo = !$recebimento->conciliado;
        $recebimento->update([
            'conciliado' => $novo,
            'data_conciliacao' => $novo ? now() : null,
        ]);

        return redirect()->route('financeiro.conciliacao-cartao.index')
            ->with('success', $novo ? 'Recebimento conciliado.' : 'Conciliação desfeita.');
    }

    public function destroy(RecebimentoCartao $recebimento)
    {
        $recebimento->delete();

        return redirect()->route('financeiro.conciliacao-cartao.index')->with('success', 'Recebimento removido.');
    }
}
