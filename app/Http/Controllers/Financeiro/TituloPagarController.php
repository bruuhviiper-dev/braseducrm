<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\CategoriaPagar;
use App\Models\CentroCusto;
use App\Models\ContaBancaria;
use App\Models\FormaPagamento;
use App\Models\Pessoa;
use App\Models\PlanoContas;
use App\Models\TituloPagar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TituloPagarController extends Controller
{
    public function index(Request $request)
    {
        $query = TituloPagar::with(['pessoa', 'categoriaPagar']);

        if ($search = $request->get('search')) {
            $query->whereHas('pessoa', fn ($q) => $q->where('nome', 'like', "%{$search}%"));
        }
        if ($situacao = $request->get('situacao')) {
            $query->where('situacao', $situacao);
        }
        if ($data_inicio = $request->get('data_inicio')) {
            $query->where('data_vencimento', '>=', $data_inicio);
        }
        if ($data_fim = $request->get('data_fim')) {
            $query->where('data_vencimento', '<=', $data_fim);
        }

        $titulos = $query->orderByDesc('data_vencimento')->paginate(15)->withQueryString();

        $totalAberto = TituloPagar::where('situacao', 'aberto')->sum('valor_original');
        $totalPago = TituloPagar::where('situacao', 'pago')->sum('valor_pago');
        $totalVencido = TituloPagar::where('situacao', 'aberto')->where('data_vencimento', '<', now())->sum('valor_original');
        $totalCancelado = TituloPagar::where('situacao', 'cancelado')->sum('valor_original');

        return view('financeiro.titulos-pagar.index', compact('titulos', 'totalAberto', 'totalPago', 'totalVencido', 'totalCancelado'));
    }

    public function create()
    {
        return view('financeiro.titulos-pagar.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);

        DB::transaction(function () use ($data, $request) {
            $n = max(1, (int) ($request->input('quantidade_parcelas') ?: 1));
            $venc = Carbon::parse($data['base']['data_vencimento']);
            $liquidado = $request->boolean('criar_liquidado');

            for ($i = 0; $i < $n; $i++) {
                $titulo = TituloPagar::create(array_merge($data['base'], [
                    'descricao' => $n > 1 ? ($data['base']['descricao'] ?? 'Título') . ' (' . ($i + 1) . '/' . $n . ')' : $data['base']['descricao'],
                    'data_vencimento' => $venc->copy()->addMonths($i)->format('Y-m-d'),
                    'situacao' => $liquidado ? 'pago' : 'aberto',
                    'valor_pago' => $liquidado ? $data['base']['valor_original'] : 0,
                    'data_pagamento' => $liquidado ? now() : null,
                ]));
                $this->salvarRateios($titulo, $data['rateios']);
            }
        });

        return redirect()->route('financeiro.titulos-pagar.index')->with('success', 'Título(s) a pagar cadastrado(s) com sucesso.');
    }

    public function edit(TituloPagar $titulos_pagar)
    {
        $titulos_pagar->load('rateios');

        return view('financeiro.titulos-pagar.form', $this->dados($titulos_pagar));
    }

    public function update(Request $request, TituloPagar $titulos_pagar)
    {
        $data = $this->validar($request);
        DB::transaction(function () use ($titulos_pagar, $data) {
            $titulos_pagar->update($data['base']);
            $this->salvarRateios($titulos_pagar, $data['rateios']);
        });

        return redirect()->route('financeiro.titulos-pagar.index')->with('success', 'Título a pagar atualizado com sucesso.');
    }

    public function destroy(TituloPagar $titulos_pagar)
    {
        $titulos_pagar->delete();

        return redirect()->route('financeiro.titulos-pagar.index')->with('success', 'Título a pagar removido com sucesso.');
    }

    private function validar(Request $request): array
    {
        $v = $request->validate([
            'pessoa_id' => 'required|exists:pessoas,id',                 // Credor
            'categoria_pagar_id' => 'nullable|exists:categorias_pagar,id',
            'forma_pagamento' => 'nullable|string|max:50',
            'plano_conta_id' => 'nullable|exists:plano_contas,id',
            'descricao' => 'nullable|string|max:255',
            'valor_original' => 'required|numeric|min:0.01',
            'quantidade_parcelas' => 'nullable|integer|min:1|max:120',
            'data_emissao' => 'required|date',
            'data_vencimento' => 'required|date',
            'referencia' => 'nullable|string|max:20',
            'numero_documento' => 'nullable|string|max:60',
            'linha_digitavel' => 'nullable|string|max:120',
            'observacoes' => 'nullable|string',
            'rateios' => 'nullable|array',
            'rateios.*.centro_custo_id' => 'nullable|exists:centros_custo,id',
            'rateios.*.valor' => 'nullable|numeric|min:0',
        ]);

        return [
            'base' => [
                'pessoa_id' => $v['pessoa_id'],
                'categoria_pagar_id' => $v['categoria_pagar_id'] ?? null,
                'forma_pagamento' => $v['forma_pagamento'] ?? null,
                'plano_conta_id' => $v['plano_conta_id'] ?? null,
                'descricao' => $v['descricao'] ?? null,
                'valor_original' => $v['valor_original'],
                'data_emissao' => $v['data_emissao'],
                'data_vencimento' => $v['data_vencimento'],
                'referencia' => $v['referencia'] ?? null,
                'numero_documento' => $v['numero_documento'] ?? null,
                'linha_digitavel' => $v['linha_digitavel'] ?? null,
                'observacoes' => $v['observacoes'] ?? null,
            ],
            'rateios' => collect($v['rateios'] ?? [])->filter(fn ($r) => !empty($r['centro_custo_id']))->values()->all(),
        ];
    }

    private function salvarRateios(TituloPagar $titulo, array $rateios): void
    {
        $titulo->rateios()->delete();
        foreach ($rateios as $r) {
            $titulo->rateios()->create([
                'centro_custo_id' => $r['centro_custo_id'],
                'valor' => $r['valor'] ?? null,
            ]);
        }
    }

    private function dados(?TituloPagar $titulo): array
    {
        return [
            'titulo' => $titulo,
            'pessoas' => Pessoa::orderBy('nome')->get(),
            'categorias' => CategoriaPagar::orderBy('nome')->get(),
            'contas' => ContaBancaria::where('ativo', true)->orderBy('nome')->get(),
            'formasPagamento' => FormaPagamento::orderBy('nome')->get(),
            'planosConta' => PlanoContas::orderBy('nome')->get(),
            'centrosCusto' => CentroCusto::where('ativo', true)->orderBy('nome')->get(),
        ];
    }
}
