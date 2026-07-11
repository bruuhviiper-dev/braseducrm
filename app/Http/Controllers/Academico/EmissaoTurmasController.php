<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Academico\Concerns\EmiteRelatorio;
use App\Models\EmissaoLayout;
use App\Models\InstituicaoEnsino;
use App\Models\Modulo;
use App\Models\PeriodoLetivo;
use App\Models\Turma;
use App\Models\TurmaMontada;
use Illuminate\Http\Request;

/**
 * 184 Emissão de Turmas Montadas — construtor de relatório dinâmico (padrão EDUQ):
 * abas Filtros | Layout de Página, Layouts salvos e export PDF/CSV/XLSX.
 */
class EmissaoTurmasController extends Controller
{
    use EmiteRelatorio;

    private const FUNCAO = 184;

    private function catalogo(): array
    {
        return [
            'sigla' => ['Sigla', fn (TurmaMontada $t) => $t->sigla ?? '—'],
            'turma_montada' => ['Turma Montada', fn (TurmaMontada $t) => $t->nome ?? ('TM #' . $t->id)],
            'turma' => ['Turma', fn (TurmaMontada $t) => $t->turma?->nome ?? '—'],
            'curso' => ['Curso', fn (TurmaMontada $t) => $t->turma?->curso?->nome ?? '—'],
            'modulo' => ['Módulo', fn (TurmaMontada $t) => $t->modulo?->nome ?? '—'],
            'periodo' => ['Período Letivo', fn (TurmaMontada $t) => $t->periodoLetivo?->nome ?? '—'],
            'situacao' => ['Situação', fn (TurmaMontada $t) => ucfirst($t->situacao ?? '—')],
            'data_inicio' => ['Início', fn (TurmaMontada $t) => $t->data_inicio?->format('d/m/Y') ?? '—'],
            'data_fim' => ['Fim', fn (TurmaMontada $t) => $t->data_fim?->format('d/m/Y') ?? '—'],
            'matriculados' => ['Matriculados', fn (TurmaMontada $t) => (string) $t->matriculas()->whereIn('situacao', ['ativa', 'confirmada'])->count()],
        ];
    }

    private function colunasPadrao(): array
    {
        return ['turma_montada', 'turma', 'curso', 'modulo', 'situacao'];
    }

    public function index(Request $request)
    {
        $catalogo = collect($this->catalogo())->map(fn ($v) => $v[0]);
        $layouts = EmissaoLayout::where('user_id', auth()->id())->where('funcao_codigo', self::FUNCAO)->orderBy('nome')->get();
        $layoutAtual = $request->filled('layout_id')
            ? $layouts->firstWhere('id', (int) $request->layout_id)
            : $layouts->firstWhere('padrao', true);

        return view('academico.emissoes.turmas-montadas', [
            'catalogo' => $catalogo,
            'colunasSel' => $layoutAtual?->colunas ?? $this->colunasPadrao(),
            'layouts' => $layouts,
            'layoutAtual' => $layoutAtual,
            'instituicoes' => InstituicaoEnsino::orderBy('nome')->get(),
            'turmas' => Turma::orderBy('nome')->get(),
            'periodos' => PeriodoLetivo::orderByDesc('id')->get(),
            'modulos' => Modulo::orderBy('nome')->get(),
        ]);
    }

    private function filtrar(Request $request)
    {
        $q = TurmaMontada::with(['turma.curso', 'modulo', 'periodoLetivo']);
        if ($request->filled('turmas')) {
            $q->whereIn('turma_id', (array) $request->turmas);
        }
        if ($request->filled('periodos')) {
            $q->whereIn('periodo_letivo_id', (array) $request->periodos);
        }
        if ($request->filled('modulos')) {
            $q->whereIn('modulo_id', (array) $request->modulos);
        }
        if ($request->boolean('somente_finalizadas')) {
            $q->where('situacao', 'finalizada');
        }
        if ($request->boolean('somente_ativas')) {
            $q->where('ativo', true);
        }
        if (! $request->boolean('inativos')) {
            $q->where('ativo', true);
        }
        if ($ini = $request->inicio) {
            $q->whereDate('data_inicio', '>=', $ini);
        }
        if ($fim = $request->fim) {
            $q->whereDate('data_inicio', '<=', $fim);
        }

        return $q->orderByDesc('id')->get();
    }

    public function emitir(Request $request)
    {
        $catalogo = $this->catalogo();
        $colunas = array_values(array_filter((array) $request->input('colunas', $this->colunasPadrao()), fn ($c) => isset($catalogo[$c])));
        if (empty($colunas)) {
            $colunas = $this->colunasPadrao();
        }

        $turmas = $this->filtrar($request);
        $cabecalho = array_map(fn ($c) => $catalogo[$c][0], $colunas);
        $linhas = $turmas->map(fn ($t) => array_map(fn ($c) => $catalogo[$c][1]($t), $colunas))->all();

        return $this->emitirRelatorio(
            (string) $request->input('formato', 'pdf'),
            'Emissão de Turmas Montadas',
            'Total: ' . count($linhas) . ' turma(s)',
            $cabecalho, $linhas, 'turmas_montadas',
            $request->input('orientacao', 'landscape'), $request->input('papel', 'a4')
        );
    }

    public function salvarLayout(Request $request)
    {
        $v = $request->validate([
            'nome' => 'required|string|max:100',
            'colunas' => 'required|array|min:1',
            'colunas.*' => 'string',
            'padrao' => 'nullable|boolean',
        ]);
        if ($request->boolean('padrao')) {
            EmissaoLayout::where('user_id', auth()->id())->where('funcao_codigo', self::FUNCAO)->update(['padrao' => false]);
        }
        EmissaoLayout::create([
            'user_id' => auth()->id(),
            'funcao_codigo' => self::FUNCAO,
            'nome' => $v['nome'],
            'colunas' => array_values($v['colunas']),
            'padrao' => $request->boolean('padrao'),
        ]);

        return back()->with('success', 'Layout salvo.');
    }

    public function excluirLayout(EmissaoLayout $layout)
    {
        abort_unless($layout->user_id === auth()->id(), 403);
        $layout->delete();

        return back()->with('success', 'Layout removido.');
    }
}
