<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Academico\Concerns\EmiteRelatorio;
use App\Models\Disciplina;
use App\Models\EmissaoLayout;
use App\Models\Enturmacao;
use App\Models\TurmaMontada;
use Illuminate\Http\Request;

/** 305 Emissão de Disciplinas dos Alunos — report-builder (padrão EDUQ). */
class EmissaoDisciplinasAlunosController extends Controller
{
    use EmiteRelatorio;

    private const FUNCAO = 305;

    /** Cada linha é uma enturmação (aluno × disciplina). */
    private function catalogo(): array
    {
        return [
            'aluno' => ['Aluno', fn (Enturmacao $e) => $e->matricula?->aluno?->pessoa?->nome ?? '—'],
            'matricula' => ['Matrícula', fn (Enturmacao $e) => $e->matricula?->numero_matricula ?? '—'],
            'turma' => ['Turma Montada', fn (Enturmacao $e) => $e->turmaMontada?->nome ?? '—'],
            'disciplina' => ['Disciplina', fn (Enturmacao $e) => $e->disciplina?->nome ?? '—'],
            'tipo' => ['Tipo', fn (Enturmacao $e) => ucfirst($e->tipo ?? 'normal')],
            'inicio' => ['Início', fn (Enturmacao $e) => $e->data_inicio?->format('d/m/Y') ?? '—'],
            'situacao' => ['Situação da Matrícula', fn (Enturmacao $e) => ucfirst($e->matricula?->situacao ?? '—')],
        ];
    }

    private function colunasPadrao(): array
    {
        return ['aluno', 'turma', 'disciplina', 'tipo'];
    }

    public function index(Request $request)
    {
        $layouts = EmissaoLayout::layoutsDe(self::FUNCAO);
        $layoutAtual = $request->filled('layout_id') ? $layouts->firstWhere('id', (int) $request->layout_id) : $layouts->firstWhere('padrao', true);

        return view('academico.emissoes.disciplinas-alunos', [
            'catalogo' => collect($this->catalogo())->map(fn ($v) => $v[0]),
            'colunasSel' => $layoutAtual?->colunas ?? $this->colunasPadrao(),
            'layouts' => $layouts,
            'layoutAtual' => $layoutAtual,
            'turmasMontadas' => TurmaMontada::with('turma')->orderByDesc('id')->get(),
            'disciplinas' => Disciplina::orderBy('nome')->get(),
            'situacoes' => ['ativa', 'nao_confirmada', 'confirmada', 'trancada', 'cancelada', 'concluida'],
            'tiposDisciplina' => ['normal', 'equivalente', 'optativa'],
        ]);
    }

    public function emitir(Request $request)
    {
        $catalogo = $this->catalogo();
        $colunas = array_values(array_filter((array) $request->input('colunas', $this->colunasPadrao()), fn ($c) => isset($catalogo[$c]))) ?: $this->colunasPadrao();

        $q = Enturmacao::with(['matricula.aluno.pessoa', 'turmaMontada', 'disciplina']);
        if ($request->filled('turmas_montadas')) {
            $q->whereIn('turma_montada_id', (array) $request->turmas_montadas);
        }
        if ($request->filled('disciplinas')) {
            $q->whereIn('disciplina_id', (array) $request->disciplinas);
        }
        if ($request->filled('tipos')) {
            $q->whereIn('tipo', (array) $request->tipos);
        }
        if ($ini = $request->matricula_inicio) {
            $q->whereHas('matricula', fn ($m) => $m->whereDate('data_matricula', '>=', $ini));
        }
        if ($fim = $request->matricula_fim) {
            $q->whereHas('matricula', fn ($m) => $m->whereDate('data_matricula', '<=', $fim));
        }
        if ($request->filled('situacoes')) {
            $q->whereHas('matricula', fn ($m) => $m->whereIn('situacao', (array) $request->situacoes));
        }
        $enturmacoes = $q->get()->sortBy(fn ($e) => $e->matricula?->aluno?->pessoa?->nome)->values();

        $cabecalho = array_map(fn ($c) => $catalogo[$c][0], $colunas);
        $linhas = $enturmacoes->map(fn ($e) => array_map(fn ($c) => $catalogo[$c][1]($e), $colunas))->all();

        return $this->emitirRelatorio((string) $request->input('formato', 'pdf'), 'Emissão de Disciplinas dos Alunos', 'Total: ' . count($linhas) . ' registro(s)', $cabecalho, $linhas, 'disciplinas_alunos', $request->input('orientacao', 'landscape'), $request->input('papel', 'a4'));
    }
}
