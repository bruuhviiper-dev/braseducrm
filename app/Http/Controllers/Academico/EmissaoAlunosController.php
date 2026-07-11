<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Academico\Concerns\EmiteRelatorio;
use App\Models\Curso;
use App\Models\EmissaoLayout;
use App\Models\Grau;
use App\Models\InstituicaoEnsino;
use App\Models\Matricula;
use App\Models\PeriodoLetivo;
use App\Models\TurmaMontada;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * 79 Emissão de Alunos Matriculados — construtor de relatório dinâmico (padrão EDUQ):
 * abas Filtros | Colunas | Layout de Página, dropdown de Layouts salvos, e export PDF/CSV/XLSX.
 */
class EmissaoAlunosController extends Controller
{
    use EmiteRelatorio;

    private const FUNCAO = 79;

    /** Catálogo de colunas disponíveis: chave => [rótulo, resolvedor]. */
    private function catalogo(): array
    {
        return [
            'matricula' => ['Matrícula', fn (Matricula $m) => $m->numero_matricula ?? $m->id],
            'aluno' => ['Aluno', fn (Matricula $m) => $m->aluno?->pessoa?->nome ?? '—'],
            'cpf' => ['CPF', fn (Matricula $m) => $m->aluno?->pessoa?->cpf ?? '—'],
            'email' => ['E-mail', fn (Matricula $m) => $m->aluno?->pessoa?->email ?? '—'],
            'celular' => ['Celular', fn (Matricula $m) => $m->aluno?->pessoa?->celular ?? '—'],
            'curso' => ['Curso', fn (Matricula $m) => $m->turma?->curso?->nome ?? '—'],
            'turma' => ['Turma', fn (Matricula $m) => $m->turmaMontada?->nome ?? $m->turma?->nome ?? '—'],
            'situacao' => ['Situação', fn (Matricula $m) => ucfirst($m->situacao)],
            'forma_ingresso' => ['Forma de Ingresso', fn (Matricula $m) => $m->formaIngresso?->nome ?? '—'],
            'data_matricula' => ['Data da Matrícula', fn (Matricula $m) => $m->data_matricula?->format('d/m/Y') ?? '—'],
            'data_inicio' => ['Início das Aulas', fn (Matricula $m) => $m->data_inicio_aulas?->format('d/m/Y') ?? '—'],
            'previsao_conclusao' => ['Previsão de Conclusão', fn (Matricula $m) => $m->previsao_conclusao?->format('d/m/Y') ?? '—'],
            'operador' => ['Operador', fn (Matricula $m) => $m->consultor?->nome ?? '—'],
            'como_conheceu' => ['Como conheceu', fn (Matricula $m) => $m->como_conheceu ?? '—'],
        ];
    }

    /** Colunas padrão quando não há layout escolhido. */
    private function colunasPadrao(): array
    {
        return ['matricula', 'aluno', 'curso', 'turma', 'situacao'];
    }

    public function index(Request $request)
    {
        $catalogo = collect($this->catalogo())->map(fn ($v) => $v[0]); // chave => rótulo
        $layouts = EmissaoLayout::where('user_id', auth()->id())->where('funcao_codigo', self::FUNCAO)->orderBy('nome')->get();

        // layout selecionado (ou padrão do usuário, ou colunas padrão)
        $layoutAtual = $request->filled('layout_id')
            ? $layouts->firstWhere('id', (int) $request->layout_id)
            : $layouts->firstWhere('padrao', true);
        $colunasSel = $layoutAtual?->colunas ?? $this->colunasPadrao();

        return view('academico.emissoes.alunos-matriculados', [
            'catalogo' => $catalogo,
            'colunasSel' => $colunasSel,
            'layouts' => $layouts,
            'layoutAtual' => $layoutAtual,
            'cursos' => Curso::where('ativo', true)->orderBy('nome')->get(),
            'graus' => Grau::orderBy('nome')->get(),
            'turmasMontadas' => TurmaMontada::orderBy('nome')->get(),
            'periodos' => PeriodoLetivo::orderByDesc('id')->get(),
            'instituicoes' => InstituicaoEnsino::orderBy('nome')->get(),
            'operadores' => User::where('ativo', true)->orderBy('nome')->get(),
            'situacoes' => ['ativa', 'nao_confirmada', 'confirmada', 'trancada', 'cancelada', 'concluida', 'transferida', 'evadida'],
        ]);
    }

    /** Aplica os filtros do EDUQ à query de matrículas. */
    private function filtrar(Request $request)
    {
        $q = Matricula::with(['aluno.pessoa', 'turma.curso', 'turmaMontada', 'formaIngresso', 'consultor']);

        // faixas de data
        $faixa = function ($campo, $ini, $fim) use ($q) {
            if ($ini) {
                $q->whereDate($campo, '>=', $ini);
            }
            if ($fim) {
                $q->whereDate($campo, '<=', $fim);
            }
        };
        $faixa('data_matricula', $request->matricula_inicio, $request->matricula_fim);
        $faixa('previsao_conclusao', $request->previsao_inicio, $request->previsao_fim);
        $faixa('created_at', $request->criacao_inicio, $request->criacao_fim);

        // multi-selects
        if ($request->filled('cursos')) {
            $q->whereHas('turma', fn ($t) => $t->whereIn('curso_id', (array) $request->cursos));
        }
        if ($request->filled('turmas_montadas')) {
            $q->whereIn('turma_montada_id', (array) $request->turmas_montadas);
        }
        if ($request->filled('situacoes')) {
            $q->whereIn('situacao', (array) $request->situacoes);
        }
        if ($request->filled('operadores')) {
            $q->whereIn('consultor_id', (array) $request->operadores);
        }
        // toggles
        if ($request->boolean('ocultar_blacklist')) {
            $q->whereHas('aluno.pessoa', fn ($p) => $p->where('blacklist', false));
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

        $matriculas = $this->filtrar($request);
        $cabecalho = array_map(fn ($c) => $catalogo[$c][0], $colunas);
        $linhas = $matriculas->map(fn ($m) => array_map(fn ($c) => $catalogo[$c][1]($m), $colunas))->all();

        return $this->emitirRelatorio(
            (string) $request->input('formato', 'pdf'),
            'Emissão de Alunos Matriculados',
            'Total: ' . count($linhas) . ' matrícula(s)',
            $cabecalho, $linhas, 'alunos_matriculados',
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
            'filtros' => $request->input('filtros', []),
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
