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
use Illuminate\Support\Facades\DB;

class AcademicoEmissaoController extends Controller
{
    private const DIAS = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];

    /** Página com as emissões disponíveis e seus filtros. */
    public function index()
    {
        $turmasMontadas = TurmaMontada::with('turma')->orderByDesc('id')->get();
        $matrizes = \App\Models\MatrizCurricular::with('curso')->orderBy('nome')->get();
        $profissionais = \App\Models\Profissional::with('pessoa')->get();
        $disciplinas = \App\Models\Disciplina::orderBy('nome')->get();

        return view('academico.emissoes.index', compact('turmasMontadas', 'matrizes', 'profissionais', 'disciplinas'));
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

        // EDUQ: o volume de estudantes contabiliza estritamente os alunos com "Matrícula Confirmada"
        $confirmadosPorTurma = Matricula::whereIn('situacao', ['confirmada', 'ativa'])
            ->selectRaw('turma_montada_id, count(*) as total')
            ->groupBy('turma_montada_id')->pluck('total', 'turma_montada_id');

        $linhas = $horarios->map(fn ($h) => [
            $h->profissional?->pessoa?->nome ?? '— (sem professor)',
            $h->disciplina?->nome ?? '—',
            $h->turmaMontada?->nome ?? '—',
            self::DIAS[$h->dia_semana] ?? $h->dia_semana,
            substr($h->hora_inicio, 0, 5),
            substr($h->hora_fim, 0, 5),
            (string) ($confirmadosPorTurma[$h->turma_montada_id] ?? 0),
        ]);

        return $this->pdf('Emissão de Horários dos Professores', null,
            ['Professor', 'Disciplina', 'Turma Montada', 'Dia', 'Início', 'Fim', 'Alunos Confirmados'], $linhas, 'horarios_professores');
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

    /** Emissão de Documentos (210) — situação de entrega dos documentos. */
    public function documentos()
    {
        $entregas = \App\Models\EntregaDocumento::with(['matricula.aluno.pessoa', 'documento'])
            ->orderByDesc('id')->get();

        $linhas = $entregas->map(fn ($e) => [
            $e->matricula?->aluno?->pessoa?->nome ?? '—',
            $e->documento?->nome ?? '—',
            $e->entregue ? 'Entregue' : 'Pendente',
            optional($e->data_entrega)->format('d/m/Y') ?? '—',
        ]);

        return $this->pdf('Emissão de Documentos', 'Situação de entrega',
            ['Aluno', 'Documento', 'Situação', 'Data de Entrega'], $linhas, 'documentos');
    }

    /** Emissão da Matriz Curricular (27). */
    public function matrizCurricular(Request $request)
    {
        $request->validate(['matriz_curricular_id' => 'required|exists:matrizes_curriculares,id']);
        $matriz = \App\Models\MatrizCurricular::with('curso')->findOrFail($request->matriz_curricular_id);

        $disciplinas = DB::table('matriz_disciplinas')
            ->join('disciplinas', 'matriz_disciplinas.disciplina_id', '=', 'disciplinas.id')
            ->leftJoin('modulos', 'matriz_disciplinas.modulo_id', '=', 'modulos.id')
            ->where('matriz_disciplinas.matriz_curricular_id', $matriz->id)
            ->orderBy('matriz_disciplinas.ordem')
            ->get(['disciplinas.nome as disciplina', 'modulos.nome as modulo', 'matriz_disciplinas.carga_horaria', 'matriz_disciplinas.creditos']);

        $linhas = $disciplinas->map(fn ($d) => [$d->disciplina, $d->modulo ?? '—', $d->carga_horaria ?? '—', $d->creditos ?? '—']);

        return $this->pdf('Emissão da Matriz Curricular', ($matriz->curso?->nome ? $matriz->curso->nome . ' — ' : '') . $matriz->nome,
            ['Disciplina', 'Módulo', 'Carga Horária', 'Créditos'], $linhas, 'matriz_curricular');
    }

    /** Emissão de Disciplinas dos Alunos (305). */
    public function disciplinasAlunos()
    {
        $linhas = DB::table('matriculas')
            ->join('alunos', 'matriculas.aluno_id', '=', 'alunos.id')
            ->join('pessoas', 'alunos.pessoa_id', '=', 'pessoas.id')
            ->join('turmas', 'matriculas.turma_id', '=', 'turmas.id')
            ->join('matriz_disciplinas', 'turmas.matriz_curricular_id', '=', 'matriz_disciplinas.matriz_curricular_id')
            ->join('disciplinas', 'matriz_disciplinas.disciplina_id', '=', 'disciplinas.id')
            ->orderBy('pessoas.nome')
            ->get(['pessoas.nome as aluno', 'turmas.nome as turma', 'disciplinas.nome as disciplina'])
            ->map(fn ($r) => [$r->aluno, $r->turma, $r->disciplina]);

        return $this->pdf('Emissão de Disciplinas dos Alunos', null,
            ['Aluno', 'Turma', 'Disciplina'], $linhas, 'disciplinas_alunos');
    }

    /** Emissão de Pendências de Notas e Faltas (249). */
    public function pendenciasNotasFaltas(Request $request)
    {
        $request->validate(['turma_montada_id' => 'required|exists:turmas_montadas,id']);
        $tm = TurmaMontada::with('turma')->findOrFail($request->turma_montada_id);
        $matriculas = Matricula::with('aluno.pessoa')->where('turma_montada_id', $tm->id)->get();

        $linhas = collect();
        foreach ($matriculas as $m) {
            $temNota = Nota::where('matricula_id', $m->id)->whereNotNull('nota')->exists();
            $temFreq = Frequencia::where('matricula_id', $m->id)->exists();
            if (!$temNota || !$temFreq) {
                $linhas->push([
                    $m->aluno?->pessoa?->nome ?? '—',
                    $temNota ? 'OK' : 'Sem notas',
                    $temFreq ? 'OK' : 'Sem frequência',
                ]);
            }
        }

        return $this->pdf('Emissão de Pendências de Notas e Faltas', 'Turma Montada: ' . ($tm->nome ?? $tm->turma?->nome ?? ('#' . $tm->id)),
            ['Aluno', 'Notas', 'Frequência'], $linhas, 'pendencias_notas_faltas');
    }

    /** Declaração de Aula Ministrada (114). */
    public function declaracaoAula(Request $request)
    {
        $data = $request->validate([
            'profissional_id' => 'required|exists:profissionais,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);

        $professor = \App\Models\Profissional::with('pessoa')->findOrFail($data['profissional_id']);
        $disciplina = \App\Models\Disciplina::findOrFail($data['disciplina_id']);
        $inicio = \Illuminate\Support\Carbon::parse($data['data_inicio']);
        $fim = \Illuminate\Support\Carbon::parse($data['data_fim']);

        $pdf = Pdf::loadView('emissoes.academico.declaracao-aula', compact('professor', 'disciplina', 'inicio', 'fim'));
        return $pdf->stream('declaracao_aula.pdf');
    }

    private function pdf(string $titulo, ?string $subtitulo, array $colunas, $linhas, string $arquivo)
    {
        $linhas = collect($linhas)->map(fn ($l) => array_values((array) $l))->all();
        $pdf = Pdf::loadView('emissoes.academico.relatorio', compact('titulo', 'subtitulo', 'colunas', 'linhas'))
            ->setPaper('a4', 'landscape');
        return $pdf->stream($arquivo . '.pdf');
    }
}
