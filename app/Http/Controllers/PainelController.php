<?php

namespace App\Http\Controllers;

use App\Models\Oportunidade;
use App\Models\EtapaFunil;
use App\Models\TituloReceber;
use App\Models\TituloPagar;
use App\Models\Matricula;
use App\Models\Interessado;
use Illuminate\Support\Carbon;

class PainelController extends Controller
{
    public function comercial()
    {
        // Oportunidades por situação
        $porSituacao = Oportunidade::selectRaw('situacao, count(*) as total')
            ->groupBy('situacao')->pluck('total', 'situacao');

        // Oportunidades por etapa do funil
        $etapas = EtapaFunil::orderBy('ordem')->get();
        $porEtapa = [];
        foreach ($etapas as $etapa) {
            $porEtapa[$etapa->nome] = Oportunidade::where('etapa_funil_id', $etapa->id)
                ->where('situacao', 'aberta')->count();
        }

        // Valor por situação
        $valorGanho = Oportunidade::where('situacao', 'ganha')->sum('valor');
        $valorAberto = Oportunidade::where('situacao', 'aberta')->sum('valor');

        $stats = [
            'interessados' => Interessado::count(),
            'oportunidades' => Oportunidade::count(),
            'ganhas' => Oportunidade::where('situacao', 'ganha')->count(),
            'valor_ganho' => $valorGanho,
            'valor_aberto' => $valorAberto,
        ];

        return view('paineis.comercial', compact('porSituacao', 'porEtapa', 'stats'));
    }

    /** Painel Financeiro Geral (138): KPIs + A Receber / A Pagar + evolução (fiel ao EDUQ). */
    public function financeiro()
    {
        $hoje = now();

        // A RECEBER
        $recTotal = (float) TituloReceber::whereIn('situacao', ['aberto', 'pago'])->sum('valor_original');
        $recRecebidas = (float) TituloReceber::where('situacao', 'pago')->sum('valor_pago');
        $recVencidas = (float) TituloReceber::where('situacao', 'aberto')->whereDate('data_vencimento', '<', $hoje)->sum('valor_original');
        $recAVencer = (float) TituloReceber::where('situacao', 'aberto')->whereDate('data_vencimento', '>=', $hoje)->sum('valor_original');

        // A PAGAR
        $pagTotal = (float) TituloPagar::whereIn('situacao', ['aberto', 'pago'])->sum('valor_original');
        $pagPagas = (float) TituloPagar::where('situacao', 'pago')->sum('valor_pago');
        $pagVencidas = (float) TituloPagar::where('situacao', 'aberto')->whereDate('data_vencimento', '<', $hoje)->sum('valor_original');
        $pagAVencer = (float) TituloPagar::where('situacao', 'aberto')->whereDate('data_vencimento', '>=', $hoje)->sum('valor_original');

        $pct = fn ($parte, $total) => $total > 0 ? round($parte / $total * 100, 2) : 0;

        $kpis = [
            'resultado_previsto' => $recTotal - $pagTotal,
            'resultado_realizado' => $recRecebidas - $pagPagas,
            'taxa_realizada' => $pct($recRecebidas, $recTotal),
            'saldo_acumulado' => $recRecebidas - $pagPagas,
            'inadimplencia' => $pct($recVencidas, $recTotal),
        ];

        $aReceber = [
            'total' => $recTotal,
            'recebidas' => ['valor' => $recRecebidas, 'pct' => $pct($recRecebidas, $recTotal)],
            'vencidas' => ['valor' => $recVencidas, 'pct' => $pct($recVencidas, $recTotal)],
            'a_vencer' => ['valor' => $recAVencer, 'pct' => $pct($recAVencer, $recTotal)],
        ];
        $aPagar = [
            'total' => $pagTotal,
            'pagas' => ['valor' => $pagPagas, 'pct' => $pct($pagPagas, $pagTotal)],
            'vencidas' => ['valor' => $pagVencidas, 'pct' => $pct($pagVencidas, $pagTotal)],
            'a_vencer' => ['valor' => $pagAVencer, 'pct' => $pct($pagAVencer, $pagTotal)],
        ];

        // Evolução receitas x despesas (6 meses)
        $meses = [];
        $receitas = [];
        $despesas = [];
        for ($i = 5; $i >= 0; $i--) {
            $ref = Carbon::now()->subMonths($i);
            $meses[] = $ref->format('m/Y');
            $receitas[] = (float) TituloReceber::whereYear('data_vencimento', $ref->year)->whereMonth('data_vencimento', $ref->month)->sum('valor_original');
            $despesas[] = (float) TituloPagar::whereYear('data_vencimento', $ref->year)->whereMonth('data_vencimento', $ref->month)->sum('valor_original');
        }

        return view('paineis.financeiro', compact('kpis', 'aReceber', 'aPagar', 'meses', 'receitas', 'despesas'));
    }

    /** Painel Acadêmico 188 (doc revisão): cards totalizadores + filtros de período/curso/turma +
     *  gráficos de Matrículas Ativas por Gênero e por Região (estado). */
    public function academico(\Illuminate\Http\Request $request)
    {
        // Totalizadores (Geral) — independentes do período (fiel ao EDUQ, campos com *)
        $totais = [
            'total' => Matricula::count(),
            'concluidas' => Matricula::where('situacao', 'concluida')->count(),
            'ativas' => Matricula::whereIn('situacao', ['ativa', 'confirmada'])->count(),
            'canceladas' => Matricula::whereIn('situacao', ['cancelada', 'evadida', 'desistente'])->count(),
            'pausadas' => Matricula::where('situacao', 'trancada')->count(),
        ];

        // Filtros do período
        $inicio = $request->filled('inicio') ? $request->date('inicio') : now()->startOfMonth();
        $fim = $request->filled('fim') ? $request->date('fim') : now()->endOfMonth();
        $cursoId = $request->input('curso_id');
        $turmaId = $request->input('turma_montada_id');

        $base = Matricula::query()
            ->when($cursoId, fn ($q) => $q->whereHas('turma', fn ($t) => $t->where('curso_id', $cursoId)))
            ->when($turmaId, fn ($q) => $q->where('turma_montada_id', $turmaId));

        $noPeriodo = fn ($situacoes = null) => (clone $base)->whereBetween('data_matricula', [$inicio, $fim])
            ->when($situacoes, fn ($q) => $q->whereIn('situacao', (array) $situacoes))->count();

        $periodo = [
            'novas' => $noPeriodo(),
            'concluidas' => $noPeriodo('concluida'),
            'canceladas' => $noPeriodo(['cancelada', 'evadida']),
            'pausadas' => $noPeriodo('trancada'),
        ];

        // Gráfico 1: matrículas ativas por gênero (demo)
        $generos = \App\Models\Pessoa::whereHas('aluno.matriculas', fn ($q) => $q->whereIn('situacao', ['ativa', 'confirmada']))
            ->select('sexo', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('sexo')
            ->orderByDesc('total')
            ->pluck('total', 'sexo')
            ->mapWithKeys(fn ($v, $k) => [$k ?: 'Não informado' => $v]);

        // Gráfico 2: matrículas por região (UF do endereço da pessoa)
        $regioes = \App\Models\Pessoa::whereHas('aluno.matriculas', fn ($q) => $q->whereIn('situacao', ['ativa', 'confirmada']))
            ->select('uf', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('uf')
            ->orderByDesc('total')
            ->limit(10)
            ->pluck('total', 'uf')
            ->mapWithKeys(fn ($v, $k) => [$k ?: 'Sem UF' => $v]);

        // Filtros de cascata para a view
        $cursos = \App\Models\Curso::where('ativo', true)->orderBy('nome')->get();
        $turmas = \App\Models\TurmaMontada::where('ativo', true)->orderBy('nome')->get();

        return view('paineis.academico', compact('totais', 'periodo', 'inicio', 'fim', 'request', 'generos', 'regioes', 'cursos', 'turmas'));
    }
}
