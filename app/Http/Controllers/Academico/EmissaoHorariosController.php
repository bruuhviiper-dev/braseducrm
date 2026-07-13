<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Academico\Concerns\EmiteRelatorio;
use App\Models\EmissaoLayout;
use App\Models\Horario;
use App\Models\Matricula;
use App\Models\PeriodoLetivo;
use App\Models\Profissional;
use App\Models\Turma;
use Illuminate\Http\Request;

/** 185 Emissão de Horários dos Professores — report-builder (padrão EDUQ). */
class EmissaoHorariosController extends Controller
{
    use EmiteRelatorio;

    private const FUNCAO = 185;
    private const DIAS = [0 => 'Domingo', 1 => 'Segunda', 2 => 'Terça', 3 => 'Quarta', 4 => 'Quinta', 5 => 'Sexta', 6 => 'Sábado'];

    private function catalogo(): array
    {
        return [
            'professor' => ['Professor', fn (Horario $h) => $h->profissional?->pessoa?->nome ?? '— (sem professor)'],
            'disciplina' => ['Disciplina', fn (Horario $h) => $h->disciplina?->nome ?? '—'],
            'turma_montada' => ['Turma Montada', fn (Horario $h) => $h->turmaMontada?->nome ?? '—'],
            'sala' => ['Sala', fn (Horario $h) => $h->sala?->nome ?? '—'],
            'dia' => ['Dia', fn (Horario $h) => self::DIAS[$h->dia_semana] ?? $h->dia_semana],
            'inicio' => ['Início', fn (Horario $h) => substr($h->hora_inicio, 0, 5)],
            'fim' => ['Fim', fn (Horario $h) => substr($h->hora_fim, 0, 5)],
            'alunos' => ['Alunos Confirmados', fn (Horario $h) => (string) Matricula::where('turma_montada_id', $h->turma_montada_id)->whereIn('situacao', ['confirmada', 'ativa'])->count()],
        ];
    }

    private function colunasPadrao(): array
    {
        return ['professor', 'disciplina', 'turma_montada', 'dia', 'inicio', 'fim'];
    }

    public function index(Request $request)
    {
        $layouts = EmissaoLayout::layoutsDe(self::FUNCAO);
        $layoutAtual = $request->filled('layout_id') ? $layouts->firstWhere('id', (int) $request->layout_id) : $layouts->firstWhere('padrao', true);

        return view('academico.emissoes.horarios-professores', [
            'catalogo' => collect($this->catalogo())->map(fn ($v) => $v[0]),
            'colunasSel' => $layoutAtual?->colunas ?? $this->colunasPadrao(),
            'layouts' => $layouts,
            'layoutAtual' => $layoutAtual,
            'periodos' => PeriodoLetivo::orderByDesc('id')->get(),
            'turmas' => Turma::orderBy('nome')->get(),
            'professores' => Profissional::with('pessoa')->get()->sortBy(fn ($p) => $p->pessoa?->nome)->values(),
        ]);
    }

    public function emitir(Request $request)
    {
        $catalogo = $this->catalogo();
        $colunas = array_values(array_filter((array) $request->input('colunas', $this->colunasPadrao()), fn ($c) => isset($catalogo[$c]))) ?: $this->colunasPadrao();

        $q = Horario::with(['profissional.pessoa', 'disciplina', 'turmaMontada', 'sala']);
        if ($request->filled('professores')) {
            $q->whereIn('profissional_id', (array) $request->professores);
        }
        if ($request->filled('turmas')) {
            $q->whereHas('turmaMontada', fn ($t) => $t->whereIn('turma_id', (array) $request->turmas));
        }
        $horarios = $q->orderBy('dia_semana')->orderBy('hora_inicio')->get();

        $cabecalho = array_map(fn ($c) => $catalogo[$c][0], $colunas);
        $linhas = $horarios->map(fn ($h) => array_map(fn ($c) => $catalogo[$c][1]($h), $colunas))->all();

        return $this->emitirRelatorio((string) $request->input('formato', 'pdf'), 'Emissão de Horários dos Professores', 'Total: ' . count($linhas) . ' horário(s)', $cabecalho, $linhas, 'horarios_professores', $request->input('orientacao', 'landscape'), $request->input('papel', 'a4'));
    }
}
