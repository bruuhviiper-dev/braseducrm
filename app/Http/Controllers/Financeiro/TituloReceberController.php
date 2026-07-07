<?php

namespace App\Http\Controllers\Financeiro;

use App\Http\Controllers\Controller;
use App\Models\TituloReceber;
use App\Models\Matricula;
use App\Models\Pessoa;
use App\Models\CategoriaReceber;
use App\Models\ContaBancaria;
use App\Services\BoletoCnabService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TituloReceberController extends Controller
{
    public function index(Request $request)
    {
        $query = TituloReceber::with(['pessoa', 'categoriaReceber']);

        if ($search = $request->get('search')) {
            $query->whereHas('pessoa', function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%");
            });
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

        $totalAberto = TituloReceber::where('situacao', 'aberto')->sum('valor_original');
        $totalPago = TituloReceber::where('situacao', 'pago')->sum('valor_pago');
        $totalVencido = TituloReceber::where('situacao', 'aberto')
            ->where('data_vencimento', '<', now())
            ->sum('valor_original');
        $totalCancelado = TituloReceber::where('situacao', 'cancelado')->sum('valor_original');

        return view('financeiro.titulos-receber.index', compact(
            'titulos', 'totalAberto', 'totalPago', 'totalVencido', 'totalCancelado'
        ));
    }

    public function gerarRemessa(BoletoCnabService $cnab)
    {
        if (!$cnab->configurado()) {
            return back()->with('error', 'Configure a integração de Boleto (banco, agência, conta) em Integrações antes de gerar a remessa.');
        }

        $titulos = TituloReceber::with('pessoa')
            ->where('situacao', 'aberto')
            ->orderBy('data_vencimento')
            ->get();

        if ($titulos->isEmpty()) {
            return back()->with('error', 'Não há títulos em aberto para gerar a remessa.');
        }

        $conteudo = $cnab->gerarRemessa($titulos);
        $nome = 'remessa_' . now()->format('Ymd_His') . '.rem';

        return response($conteudo, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $nome . '"',
        ]);
    }

    /** Novo (fiel ao EDUQ): "Gerar para" Matrícula/Pessoa -> Carregar dados -> parcelas. */
    public function create()
    {
        return view('financeiro.titulos-receber.gerar', $this->dadosGerar());
    }

    /** Carrega as parcelas a partir do plano de pagamento da matrícula (ou uma parcela p/ pessoa). */
    public function carregar(Request $request)
    {
        $v = $request->validate([
            'gerar_para' => 'required|in:matricula,pessoa',
            'matricula_id' => 'nullable|exists:matriculas,id',
            'pessoa_id' => 'nullable|exists:pessoas,id',
        ]);

        $parcelas = [];
        $pessoaId = null;
        $matriculaId = null;

        if ($v['gerar_para'] === 'matricula' && !empty($v['matricula_id'])) {
            $m = Matricula::with('aluno.pessoa')->find($v['matricula_id']);
            $matriculaId = $m->id;
            $pessoaId = $m->aluno?->pessoa_id;

            $n = (int) ($m->num_parcelas ?: 1);
            $valorParcela = $m->valor_parcela ?: (($m->valor_total ?: 0) - ($m->desconto ?: 0)) / max(1, $n);
            $primeiro = $m->primeiro_vencimento ? Carbon::parse($m->primeiro_vencimento) : Carbon::now()->addMonth()->day((int) ($m->dia_vencimento ?: 10));

            for ($i = 0; $i < $n; $i++) {
                $venc = $primeiro->copy()->addMonths($i);
                if ($m->dia_vencimento) {
                    $venc->day(min((int) $m->dia_vencimento, $venc->daysInMonth));
                }
                $parcelas[] = [
                    'descricao' => 'Parcela ' . ($i + 1) . '/' . $n,
                    'valor' => round($valorParcela, 2),
                    'vencimento' => $venc->format('Y-m-d'),
                ];
            }
        } else {
            $pessoaId = $v['pessoa_id'] ?? null;
            $parcelas[] = ['descricao' => 'Título avulso', 'valor' => null, 'vencimento' => Carbon::now()->addMonth()->format('Y-m-d')];
        }

        return view('financeiro.titulos-receber.gerar', array_merge($this->dadosGerar(), [
            'parcelas' => $parcelas,
            'gerarPara' => $v['gerar_para'],
            'pessoaId' => $pessoaId,
            'matriculaId' => $matriculaId,
        ]));
    }

    /** Gera os títulos a receber a partir das parcelas confirmadas. */
    public function gerar(Request $request)
    {
        $data = $request->validate([
            'pessoa_id' => 'required|exists:pessoas,id',
            'matricula_id' => 'nullable|exists:matriculas,id',
            'categoria_receber_id' => 'nullable|exists:categorias_receber,id',
            'conta_bancaria_id' => 'nullable|exists:contas_bancarias,id',
            'forma_pagamento' => 'nullable|string|max:50',
            'parcelas' => 'required|array|min:1',
            'parcelas.*.descricao' => 'nullable|string|max:120',
            'parcelas.*.valor' => 'required|numeric|min:0.01',
            'parcelas.*.vencimento' => 'required|date',
        ]);

        $count = 0;
        DB::transaction(function () use ($data, &$count) {
            foreach ($data['parcelas'] as $p) {
                TituloReceber::create([
                    'pessoa_id' => $data['pessoa_id'],
                    'matricula_id' => $data['matricula_id'] ?? null,
                    'categoria_receber_id' => $data['categoria_receber_id'] ?? null,
                    'conta_bancaria_id' => $data['conta_bancaria_id'] ?? null,
                    'valor_original' => $p['valor'],
                    'data_emissao' => now(),
                    'data_vencimento' => $p['vencimento'],
                    'forma_pagamento' => $data['forma_pagamento'] ?? null,
                    'observacoes' => $p['descricao'] ?? null,
                    'situacao' => 'aberto',
                ]);
                $count++;
            }
        });

        return redirect()->route('financeiro.titulos-receber.index')
            ->with('success', "{$count} título(s) a receber gerado(s) com sucesso.");
    }

    private function dadosGerar(): array
    {
        return [
            'pessoas' => Pessoa::orderBy('nome')->get(),
            'matriculas' => Matricula::with('aluno.pessoa', 'turma')->orderByDesc('id')->get(),
            'categorias' => CategoriaReceber::orderBy('nome')->get(),
            'contas' => ContaBancaria::where('ativo', true)->orderBy('nome')->get(),
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'pessoa_id' => 'required|exists:pessoas,id',
            'categoria_receber_id' => 'nullable|exists:categorias_receber,id',
            'conta_bancaria_id' => 'nullable|exists:contas_bancarias,id',
            'valor_original' => 'required|numeric|min:0.01',
            'valor_desconto' => 'nullable|numeric|min:0',
            'data_emissao' => 'required|date',
            'data_vencimento' => 'required|date',
            'forma_pagamento' => 'nullable|string|max:50',
            'observacoes' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['situacao'] = 'aberto';

        TituloReceber::create($data);

        return redirect()->route('financeiro.titulos-receber.index')
            ->with('success', 'Titulo a receber cadastrado com sucesso.');
    }

    public function edit(TituloReceber $titulos_receber)
    {
        $titulo = $titulos_receber;
        $pessoas = Pessoa::orderBy('nome')->get();
        $categorias = CategoriaReceber::orderBy('nome')->get();
        $contas = ContaBancaria::where('ativo', true)->orderBy('nome')->get();

        return view('financeiro.titulos-receber.form', compact('titulo', 'pessoas', 'categorias', 'contas'));
    }

    public function update(Request $request, TituloReceber $titulos_receber)
    {
        $titulo = $titulos_receber;

        $request->validate([
            'pessoa_id' => 'required|exists:pessoas,id',
            'categoria_receber_id' => 'nullable|exists:categorias_receber,id',
            'conta_bancaria_id' => 'nullable|exists:contas_bancarias,id',
            'valor_original' => 'required|numeric|min:0.01',
            'valor_desconto' => 'nullable|numeric|min:0',
            'data_emissao' => 'required|date',
            'data_vencimento' => 'required|date',
            'forma_pagamento' => 'nullable|string|max:50',
            'observacoes' => 'nullable|string',
        ]);

        $titulo->update($request->all());

        return redirect()->route('financeiro.titulos-receber.index')
            ->with('success', 'Titulo a receber atualizado com sucesso.');
    }

    public function destroy(TituloReceber $titulos_receber)
    {
        $titulos_receber->delete();

        return redirect()->route('financeiro.titulos-receber.index')
            ->with('success', 'Titulo a receber removido com sucesso.');
    }

    /** Baixa manual (EDUQ): data real, juros/multa calculados na hora, desconto e nome do pagador real. */
    public function baixar(Request $request, TituloReceber $titulo)
    {
        $v = $request->validate([
            'valor_pago' => 'nullable|numeric|min:0',
            'data_pagamento' => 'nullable|date',
            'pagador' => 'nullable|string|max:255',
            'valor_juros' => 'nullable|numeric|min:0',
            'valor_multa' => 'nullable|numeric|min:0',
            'valor_desconto' => 'nullable|numeric|min:0',
        ]);

        $dataPagamento = $v['data_pagamento'] ?? now()->format('Y-m-d');

        // Juros/multa sugeridos pela configuração (padrão legal: juros 1% a.m. pro rata + multa de até 2%)
        $juros = $v['valor_juros'] ?? null;
        $multa = $v['valor_multa'] ?? null;
        if ($juros === null && $multa === null && Carbon::parse($dataPagamento)->gt($titulo->data_vencimento)) {
            $config = \App\Models\ConfiguracaoFinanceiro::current();
            $diasAtraso = Carbon::parse($titulo->data_vencimento)->diffInDays(Carbon::parse($dataPagamento));
            $juros = round($titulo->valor_original * ((float) ($config->juros_dia ?? 0.033) / 100) * $diasAtraso, 2);
            $multa = round($titulo->valor_original * ((float) ($config->multa_atraso ?? 2) / 100), 2);
        }

        $valorPago = $v['valor_pago']
            ?? ($titulo->valor_original - ($v['valor_desconto'] ?? $titulo->valor_desconto ?? 0) + ($juros ?? 0) + ($multa ?? 0));

        $titulo->update([
            'situacao' => 'pago',
            'valor_pago' => round($valorPago, 2),
            'valor_desconto' => $v['valor_desconto'] ?? $titulo->valor_desconto,
            'valor_juros' => $juros,
            'valor_multa' => $multa,
            'data_pagamento' => $dataPagamento,
            'pagador' => $v['pagador'] ?? null,
        ]);

        return redirect()->route('financeiro.titulos-receber.index')
            ->with('success', 'Título baixado com sucesso.');
    }

    /** Estornar pagamento ("mãozinha" do EDUQ): reabre o título imediatamente em caso de baixa por engano. */
    public function estornar(TituloReceber $titulo)
    {
        if ($titulo->situacao !== 'pago') {
            return back()->with('error', 'Apenas títulos pagos podem ser estornados.');
        }

        $titulo->update([
            'situacao' => 'aberto',
            'valor_pago' => null,
            'valor_juros' => null,
            'valor_multa' => null,
            'data_pagamento' => null,
            'pagador' => null,
        ]);

        return back()->with('success', 'Pagamento estornado: o título foi reaberto.');
    }
}
