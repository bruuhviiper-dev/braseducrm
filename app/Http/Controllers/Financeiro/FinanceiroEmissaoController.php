<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\Caixa;
use App\Models\Pessoa;
use App\Models\TituloPagar;
use App\Models\TituloReceber;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FinanceiroEmissaoController extends Controller
{
    public function index()
    {
        $pessoas = Pessoa::orderBy('nome')->get();
        $caixas = Caixa::with('contaBancaria')->whereIn('situacao', ['fechado', 'encerrado'])->orderByDesc('data_fechamento')->get();

        return view('financeiro.emissoes.index', compact('pessoas', 'caixas'));
    }

    /** 173 — Emissão de Títulos a Pagar. */
    public function titulosPagar(Request $request)
    {
        $query = TituloPagar::with(['pessoa']);
        if ($request->filled('situacao')) {
            $query->where('situacao', $request->situacao);
        }
        $titulos = $query->orderBy('data_vencimento')->get();

        $linhas = $titulos->map(fn ($t) => [
            $t->numero_documento ?? ('#' . $t->id),
            $t->pessoa?->nome ?? '—',
            $t->descricao ?? '—',
            optional($t->data_vencimento)->format('d/m/Y') ?? '—',
            'R$ ' . number_format((float) $t->valor_original, 2, ',', '.'),
            ucfirst($t->situacao),
        ]);

        return $this->pdf('Emissão de Títulos a Pagar', $request->filled('situacao') ? 'Situação: ' . ucfirst($request->situacao) : 'Todas as situações',
            ['Documento', 'Fornecedor', 'Descrição', 'Vencimento', 'Valor', 'Situação'], $linhas, 'titulos_a_pagar');
    }

    /** 66 — Emissão de Boletos Bancários (títulos a receber com boleto gerado). */
    public function boletos(Request $request)
    {
        $titulos = TituloReceber::with(['pessoa'])
            ->whereNotNull('nosso_numero')
            ->where('situacao', 'aberto')
            ->orderBy('data_vencimento')->get();

        $linhas = $titulos->map(fn ($t) => [
            $t->nosso_numero,
            $t->pessoa?->nome ?? '—',
            optional($t->data_vencimento)->format('d/m/Y') ?? '—',
            'R$ ' . number_format((float) $t->valor_original, 2, ',', '.'),
            $t->linha_digitavel ?? '—',
        ]);

        return $this->pdf('Emissão de Boletos Bancários', 'Títulos em aberto com boleto',
            ['Nosso Número', 'Sacado', 'Vencimento', 'Valor', 'Linha Digitável'], $linhas, 'boletos');
    }

    /** 113 — Emissão de Cobrança (títulos vencidos em aberto). */
    public function cobranca()
    {
        $hoje = Carbon::today();
        $titulos = TituloReceber::with('pessoa')
            ->whereIn('situacao', ['aberto', 'vencido'])
            ->whereDate('data_vencimento', '<', $hoje)
            ->orderBy('data_vencimento')->get();

        $linhas = $titulos->map(function ($t) use ($hoje) {
            $atraso = $t->data_vencimento ? $t->data_vencimento->diffInDays($hoje) : 0;
            return [
                $t->pessoa?->nome ?? '—',
                $t->numero_documento ?? ('#' . $t->id),
                optional($t->data_vencimento)->format('d/m/Y') ?? '—',
                $atraso . ' dia(s)',
                'R$ ' . number_format((float) $t->valor_original, 2, ',', '.'),
            ];
        });

        return $this->pdf('Emissão de Cobrança', 'Títulos vencidos em aberto',
            ['Devedor', 'Documento', 'Vencimento', 'Atraso', 'Valor'], $linhas, 'cobranca');
    }

    /** 93 — Conta Corrente por Pessoa (extrato de títulos a receber e a pagar). */
    public function contaCorrente(Request $request)
    {
        $request->validate(['pessoa_id' => 'required|exists:pessoas,id']);
        $pessoa = Pessoa::findOrFail($request->pessoa_id);

        $receber = TituloReceber::where('pessoa_id', $pessoa->id)->get()
            ->map(fn ($t) => [
                optional($t->data_vencimento)->format('d/m/Y') ?? '—',
                'A receber',
                $t->numero_documento ?? ('#' . $t->id),
                'R$ ' . number_format((float) $t->valor_original, 2, ',', '.'),
                ucfirst($t->situacao),
            ]);
        $pagar = TituloPagar::where('pessoa_id', $pessoa->id)->get()
            ->map(fn ($t) => [
                optional($t->data_vencimento)->format('d/m/Y') ?? '—',
                'A pagar',
                $t->numero_documento ?? ('#' . $t->id),
                'R$ ' . number_format((float) $t->valor_original, 2, ',', '.'),
                ucfirst($t->situacao),
            ]);
        $linhas = $receber->concat($pagar);

        return $this->pdf('Conta Corrente por Pessoa', $pessoa->nome,
            ['Vencimento', 'Tipo', 'Documento', 'Valor', 'Situação'], $linhas, 'conta_corrente');
    }

    /** 101 — Resumo Financeiro da Pessoa. */
    public function resumoPessoa(Request $request)
    {
        $request->validate(['pessoa_id' => 'required|exists:pessoas,id']);
        $pessoa = Pessoa::findOrFail($request->pessoa_id);

        $rec = TituloReceber::where('pessoa_id', $pessoa->id);
        $pag = TituloPagar::where('pessoa_id', $pessoa->id);

        $linhas = collect([
            ['A receber — total', 'R$ ' . number_format((float) (clone $rec)->sum('valor_original'), 2, ',', '.')],
            ['A receber — pago', 'R$ ' . number_format((float) (clone $rec)->where('situacao', 'pago')->sum('valor_pago'), 2, ',', '.')],
            ['A receber — em aberto', 'R$ ' . number_format((float) (clone $rec)->whereIn('situacao', ['aberto', 'vencido'])->sum('valor_original'), 2, ',', '.')],
            ['A pagar — total', 'R$ ' . number_format((float) (clone $pag)->sum('valor_original'), 2, ',', '.')],
            ['A pagar — em aberto', 'R$ ' . number_format((float) (clone $pag)->where('situacao', 'aberto')->sum('valor_original'), 2, ',', '.')],
        ]);

        return $this->pdf('Resumo Financeiro da Pessoa', $pessoa->nome, ['Indicador', 'Valor'], $linhas, 'resumo_financeiro_pessoa');
    }

    /** 106 — Emissão do Fechamento de Caixa. */
    public function fechamentoCaixa(Request $request)
    {
        $request->validate(['caixa_id' => 'required|exists:caixas,id']);
        $caixa = Caixa::with(['contaBancaria', 'movimentacoes'])->findOrFail($request->caixa_id);

        $entradas = $caixa->movimentacoes->where('tipo', 'entrada')->sum('valor');
        $saidas = $caixa->movimentacoes->where('tipo', 'saida')->sum('valor');

        $linhas = collect([
            ['Abertura', optional($caixa->data_abertura)->format('d/m/Y H:i') ?? '—'],
            ['Valor de abertura', 'R$ ' . number_format((float) $caixa->valor_abertura, 2, ',', '.')],
            ['Total de entradas', 'R$ ' . number_format((float) $entradas, 2, ',', '.')],
            ['Total de saídas', 'R$ ' . number_format((float) $saidas, 2, ',', '.')],
            ['Saldo apurado', 'R$ ' . number_format((float) $caixa->valor_abertura + $entradas - $saidas, 2, ',', '.')],
            ['Fechamento', optional($caixa->data_fechamento)->format('d/m/Y H:i') ?? '—'],
            ['Valor de fechamento', 'R$ ' . number_format((float) $caixa->valor_fechamento, 2, ',', '.')],
        ]);

        return $this->pdf('Fechamento de Caixa', 'Caixa #' . $caixa->id . ($caixa->contaBancaria ? ' — ' . $caixa->contaBancaria->nome : ''),
            ['Item', 'Valor'], $linhas, 'fechamento_caixa');
    }

    /** 180 — Emissão de Comissões (percentual sobre recebimentos pagos no período). */
    public function comissoes(Request $request)
    {
        $data = $request->validate([
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'percentual' => 'required|numeric|min:0|max:100',
        ]);

        $titulos = TituloReceber::with('pessoa')
            ->where('situacao', 'pago')
            ->whereBetween('data_pagamento', [$data['data_inicio'], $data['data_fim']])
            ->orderBy('data_pagamento')->get();

        $pct = (float) $data['percentual'];
        $linhas = $titulos->map(fn ($t) => [
            optional($t->data_pagamento)->format('d/m/Y') ?? '—',
            $t->pessoa?->nome ?? '—',
            'R$ ' . number_format((float) $t->valor_pago, 2, ',', '.'),
            number_format($pct, 2, ',', '.') . '%',
            'R$ ' . number_format((float) $t->valor_pago * $pct / 100, 2, ',', '.'),
        ]);

        $total = $titulos->sum(fn ($t) => (float) $t->valor_pago * $pct / 100);

        return $this->pdf('Emissão de Comissões', 'Período ' . Carbon::parse($data['data_inicio'])->format('d/m/Y') . ' a ' . Carbon::parse($data['data_fim'])->format('d/m/Y') . ' — Total comissões: R$ ' . number_format($total, 2, ',', '.'),
            ['Pagamento', 'Cliente', 'Valor pago', '%', 'Comissão'], $linhas, 'comissoes');
    }

    /** 116 — Emissão de Títulos a Receber. */
    public function titulosReceber(Request $request)
    {
        $query = TituloReceber::with(['pessoa', 'categoriaReceber']);
        if ($request->filled('situacao')) {
            $query->where('situacao', $request->situacao);
        }
        $linhas = $query->orderBy('data_vencimento')->get()->map(fn ($t) => [
            '#' . $t->id,
            $t->pessoa?->nome ?? '—',
            $t->categoriaReceber?->nome ?? '—',
            optional($t->data_vencimento)->format('d/m/Y') ?? '—',
            'R$ ' . number_format((float) $t->valor_original, 2, ',', '.'),
            ucfirst($t->situacao),
        ]);

        return $this->pdf('Emissão de Títulos a Receber', null,
            ['Título', 'Pessoa', 'Categoria', 'Vencimento', 'Valor', 'Situação'], $linhas, 'titulos_a_receber');
    }

    /** 161 — Emissão de Lançamentos Financeiros. */
    public function lancamentos()
    {
        $linhas = \App\Models\LancamentoFinanceiro::with(['contaBancaria', 'planoConta'])
            ->orderByDesc('data_lancamento')->get()->map(fn ($l) => [
                optional($l->data_lancamento)->format('d/m/Y') ?? '—',
                ucfirst($l->tipo),
                $l->descricao,
                $l->contaBancaria?->nome ?? '—',
                $l->planoConta?->nome ?? '—',
                'R$ ' . number_format((float) $l->valor, 2, ',', '.'),
            ]);

        return $this->pdf('Emissão de Lançamentos Financeiros', null,
            ['Data', 'Tipo', 'Descrição', 'Conta', 'Plano de Contas', 'Valor'], $linhas, 'lancamentos');
    }

    /** 162 — Emissão do Plano de Contas. */
    public function planoContas()
    {
        $linhas = \App\Models\PlanoContas::orderBy('codigo')->get()->map(fn ($c) => [
            $c->codigo,
            str_repeat('    ', max(0, ($c->nivel ?? 1) - 1)) . $c->nome,
            $c->tipo === 'sintetica' ? 'S' : 'A',
            ucfirst($c->natureza),
            $c->ativo ? 'Ativa' : 'Inativa',
        ]);

        return $this->pdf('Emissão do Plano de Contas', 'S = Sintética (agrupadora, sem movimento) · A = Analítica (operacional)',
            ['Código', 'Conta', 'S/A', 'Natureza', 'Situação'], $linhas, 'plano_de_contas');
    }

    /** 99 — Emissão de Declaração de Pagamentos (títulos quitados por pessoa). */
    public function declaracaoPagamentos(Request $request)
    {
        $query = TituloReceber::with(['pessoa', 'categoriaReceber'])->where('situacao', 'pago');
        if ($request->filled('pessoa_id')) {
            $query->where('pessoa_id', $request->pessoa_id);
        }
        $linhas = $query->orderBy('pessoa_id')->orderBy('data_pagamento')->get()->map(fn ($t) => [
            $t->pessoa?->nome ?? '—',
            $t->categoriaReceber?->nome ?? '—',
            optional($t->data_pagamento)->format('d/m/Y') ?? '—',
            'R$ ' . number_format((float) $t->valor_pago, 2, ',', '.'),
            $t->pagador ?: ($t->pessoa?->nome ?? '—'),
        ]);

        return $this->pdf('Declaração de Pagamentos', null,
            ['Pessoa', 'Categoria', 'Data do Pagamento', 'Valor Pago', 'Pagador'], $linhas, 'declaracao_pagamentos');
    }

    /** 258 — Emissão de Pagamentos (Contas a Pagar quitadas). */
    public function pagamentosContasPagar()
    {
        $linhas = TituloPagar::with('pessoa')->where('situacao', 'pago')
            ->orderByDesc('data_pagamento')->get()->map(fn ($t) => [
                $t->numero_documento ?? ('#' . $t->id),
                $t->pessoa?->nome ?? '—',
                $t->descricao ?? '—',
                optional($t->data_pagamento)->format('d/m/Y') ?? '—',
                'R$ ' . number_format((float) ($t->valor_pago ?? $t->valor_original), 2, ',', '.'),
            ]);

        return $this->pdf('Emissão de Pagamentos — Contas a Pagar', null,
            ['Documento', 'Fornecedor', 'Descrição', 'Pagamento', 'Valor'], $linhas, 'pagamentos_contas_pagar');
    }

    /** 275 — Emissão de Renegociação de Parcelas. */
    public function renegociacoes()
    {
        $linhas = TituloReceber::with('pessoa')->where('situacao', 'renegociado')
            ->orderByDesc('updated_at')->get()->map(fn ($t) => [
                '#' . $t->id,
                $t->pessoa?->nome ?? '—',
                optional($t->data_vencimento)->format('d/m/Y') ?? '—',
                'R$ ' . number_format((float) $t->valor_original, 2, ',', '.'),
                optional($t->updated_at)->format('d/m/Y') ?? '—',
            ]);

        return $this->pdf('Emissão de Renegociação de Parcelas', 'Títulos com situação Renegociado',
            ['Título', 'Pessoa', 'Vencimento Original', 'Valor', 'Renegociado em'], $linhas, 'renegociacoes');
    }

    /** 255 — Resumo de Recebimentos (Cartão). */
    public function resumoCartao()
    {
        $linhas = \App\Models\RecebimentoCartao::with('contrato')
            ->orderByDesc('id')->get()->map(fn ($r) => [
                optional($r->data_venda)->format('d/m/Y') ?? '—',
                $r->contrato?->operadora ?? '—',
                ucfirst(str_replace('_', ' ', (string) $r->modalidade)),
                'R$ ' . number_format((float) $r->valor_bruto, 2, ',', '.'),
                'R$ ' . number_format((float) $r->valor_liquido, 2, ',', '.'),
                $r->conciliado ? 'Conciliado' : 'Pendente',
            ]);

        return $this->pdf('Resumo de Recebimentos (Cartão)', null,
            ['Data', 'Operadora', 'Modalidade', 'Bruto', 'Líquido', 'Conciliação'], $linhas, 'resumo_cartao');
    }

    private function pdf(string $titulo, ?string $subtitulo, array $colunas, $linhas, string $arquivo)
    {
        $linhas = collect($linhas)->map(fn ($l) => array_values((array) $l))->all();
        $pdf = Pdf::loadView('emissoes.academico.relatorio', compact('titulo', 'subtitulo', 'colunas', 'linhas'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream($arquivo . '.pdf');
    }
}
