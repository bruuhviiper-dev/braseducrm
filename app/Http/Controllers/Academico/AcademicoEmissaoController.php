<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Frequencia;
use App\Models\Horario;
use App\Models\Matricula;
use App\Models\Nota;
use App\Models\TurmaMontada;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AcademicoEmissaoController extends Controller
{
    private const DIAS = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];

    /** Página com as emissões disponíveis e seus filtros. */
    public function index()
    {
        $turmasMontadas = TurmaMontada::with('turma')->orderByDesc('id')->get();

        return view('academico.emissoes.index', compact('turmasMontadas'));
    }

    /** Emissão de Alunos Matriculados (79). */
    public function alunosMatriculados(Request $request)
    {
        $query = Matricula::with(['aluno.pessoa', 'turma.curso']);
        if ($request->filled('situacao')) {
            $query->where('situacao', $request->situacao);
        }
        $matriculas = $query->orderByDesc('id')->get();

        $linhas = $matriculas->map(fn ($m) => [
            $m->numero_matricula ?? $m->id,
            $m->aluno?->pessoa?->nome ?? '—',
            $m->turma?->curso?->nome ?? '—',
            $m->turma?->nome ?? '—',
            ucfirst($m->situacao),
        ]);

        return $this->pdf('Emissão de Alunos Matriculados', $request->filled('situacao') ? 'Situação: ' . ucfirst($request->situacao) : 'Todas as situações',
            ['Matrícula', 'Aluno', 'Curso', 'Turma', 'Situação'], $linhas, 'alunos_matriculados');
    }

    /** Emissão de Turmas Montadas (184). */
    public function turmasMontadas()
    {
        $turmas = TurmaMontada::with(['turma.curso', 'modulo'])->orderByDesc('id')->get();

        $linhas = $turmas->map(fn ($t) => [
            $t->nome ?? ('TM #' . $t->id),
            $t->turma?->nome ?? '—',
            $t->turma?->curso?->nome ?? '—',
            $t->modulo?->nome ?? '—',
            ucfirst($t->situacao ?? '—'),
        ]);

        return $this->pdf('Emissão de Turmas Montadas', null,
            ['Turma Montada', 'Turma', 'Curso', 'Módulo', 'Situação'], $linhas, 'turmas_montadas');
    }

    /** Emissão de Horários dos Professores (185). */
    public function horariosProfessores()
    {
        $horarios = Horario::with(['profissional.pessoa', 'disciplina', 'turmaMontada', 'sala'])
            ->orderBy('dia_semana')->orderBy('hora_inicio')->get();

        $linhas = $horarios->map(fn ($h) => [
            $h->profissional?->pessoa?->nome ?? '— (sem professor)',
            $h->disciplina?->nome ?? '—',
            $h->turmaMontada?->nome ?? '—',
            self::DIAS[$h->dia_semana] ?? $h->dia_semana,
            substr($h->hora_inicio, 0, 5),
            substr($h->hora_fim, 0, 5),
        ]);

        return $this->pdf('Emissão de Horários dos Professores', null,
            ['Professor', 'Disciplina', 'Turma Montada', 'Dia', 'Início', 'Fim'], $linhas, 'horarios_professores');
    }

    /** Emissão de Notas e Faltas (60) — por turma montada. */
    public function notasFaltas(Request $request)
    {
        $request->validate(['turma_montada_id' => 'required|exists:turmas_montadas,id']);
        $tm = TurmaMontada::with('turma')->findOrFail($request->turma_montada_id);

        $matriculas = Matricula::with('aluno.pessoa')->where('turma_montada_id', $tm->id)->get();
        $notas = Nota::with('disciplina')->whereIn('matricula_id', $matriculas->pluck('id'))->get();

        $linhas = collect();
        foreach ($matriculas as $m) {
            $nm = $notas->where('matricula_id', $m->id);
            if ($nm->isEmpty()) {
                $linhas->push([$m->aluno?->pessoa?->nome ?? '—', '—', '—', '—']);
                continue;
            }
            foreach ($nm as $n) {
                $faltas = Frequencia::where('matricula_id', $m->id)
                    ->where('disciplina_id', $n->disciplina_id)->where('status', 'ausente')->count();
                $linhas->push([
                    $m->aluno?->pessoa?->nome ?? '—',
                    $n->disciplina?->nome ?? '—',
                    $n->nota !== null ? number_format($n->nota, 2, ',', '.') : '—',
                    $faltas,
                ]);
            }
        }

        return $this->pdf('Emissão de Notas e Faltas', 'Turma Montada: ' . ($tm->nome ?? $tm->turma?->nome ?? ('#' . $tm->id)),
            ['Aluno', 'Disciplina', 'Nota', 'Faltas'], $linhas, 'notas_faltas');
    }

    /** Emissão do Diário de Classe (91) — frequências por turma montada. */
    public function diarioClasse(Request $request)
    {
        $request->validate(['turma_montada_id' => 'required|exists:turmas_montadas,id']);
        $tm = TurmaMontada::with('turma')->findOrFail($request->turma_montada_id);

        $matriculas = Matricula::with('aluno.pessoa')->where('turma_montada_id', $tm->id)->get();

        $linhas = $matriculas->map(function ($m) {
            $presencas = Frequencia::where('matricula_id', $m->id)->where('status', 'presente')->count();
            $faltas = Frequencia::where('matricula_id', $m->id)->where('status', 'ausente')->count();
            $just = Frequencia::where('matricula_id', $m->id)->where('status', 'justificada')->count();
            return [$m->aluno?->pessoa?->nome ?? '—', $presencas, $faltas, $just];
        });

        return $this->pdf('Emissão do Diário de Classe', 'Turma Montada: ' . ($tm->nome ?? $tm->turma?->nome ?? ('#' . $tm->id)),
            ['Aluno', 'Presenças', 'Faltas', 'Justificadas'], $linhas, 'diario_classe');
    }

    private function pdf(string $titulo, ?string $subtitulo, array $colunas, $linhas, string $arquivo)
    {
        $linhas = collect($linhas)->map(fn ($l) => array_values((array) $l))->all();
        $pdf = Pdf::loadView('emissoes.academico.relatorio', compact('titulo', 'subtitulo', 'colunas', 'linhas'))
            ->setPaper('a4', 'landscape');
        return $pdf->stream($arquivo . '.pdf');
    }
}
