<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Frequencia;
use App\Models\Horario;
use App\Models\Matricula;
use App\Models\Nota;
use App\Models\Profissional;
use App\Models\TurmaMontada;
use Illuminate\Http\Request;

class PainelEnsinoController extends Controller
{
    private const DIAS = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];

    /** Planejamento Diário de Aulas (45): ocorrências de aula (datadas) num intervalo. */
    public function planejamentoDiario(Request $request)
    {
        $turmasMontadas = TurmaMontada::with('turma')->orderByDesc('id')->get();
        $disciplinas = \App\Models\Disciplina::where('ativo', true)->orderBy('nome')->get();
        $professores = Profissional::with('pessoa')->where('ativo', true)->get();

        $aulas = collect();
        $consultou = $request->filled(['turma_montada_id', 'inicio', 'fim']);

        if ($consultou) {
            $aulas = $this->ocorrenciasDeAula($request);

            if ($request->boolean('export')) {
                return $this->exportarPlanejamento($aulas);
            }
        }

        return view('academico.painel.planejamento-diario', compact('turmasMontadas', 'disciplinas', 'professores', 'aulas', 'consultou', 'request'));
    }

    private function ocorrenciasDeAula(Request $request): \Illuminate\Support\Collection
    {
        $inicio = \Carbon\Carbon::parse($request->inicio)->startOfDay();
        $fim = \Carbon\Carbon::parse($request->fim)->startOfDay();
        if ($fim->lt($inicio)) {
            [$inicio, $fim] = [$fim, $inicio];
        }
        if ($inicio->diffInDays($fim) > 120) {
            $fim = $inicio->copy()->addDays(120);
        }

        $horarios = Horario::with(['disciplina', 'profissional.pessoa', 'sala'])
            ->where('turma_montada_id', $request->turma_montada_id)
            ->when($request->filled('disciplina_id'), fn ($q) => $q->where('disciplina_id', $request->disciplina_id))
            ->when($request->filled('professor_id'), fn ($q) => $q->where('profissional_id', $request->professor_id))
            ->orderBy('hora_inicio')->get();

        $ocorrencias = collect();
        for ($d = $inicio->copy(); $d->lte($fim); $d->addDay()) {
            foreach ($horarios as $h) {
                if ($h->dia_semana !== $d->dayOfWeekIso) {
                    continue;
                }
                $dataStr = $d->format('Y-m-d');
                $temFreq = Frequencia::where('disciplina_id', $h->disciplina_id)->whereDate('data', $dataStr)->exists();
                if ($request->boolean('sem_frequencia') && $temFreq) {
                    continue;
                }
                $ocorrencias->push([
                    'data' => $dataStr,
                    'dia' => self::DIAS[$d->dayOfWeek] ?? '',
                    'inicio' => substr($h->hora_inicio, 0, 5),
                    'fim' => substr($h->hora_fim, 0, 5),
                    'disciplina' => $h->disciplina?->nome ?? '—',
                    'professor' => $h->profissional?->pessoa?->nome ?? '—',
                    'sala' => $h->sala?->nome ?? '—',
                    'frequencia_lancada' => $temFreq,
                ]);
            }
        }

        return $ocorrencias->sortBy(fn ($a) => $a['data'] . $a['inicio'])->values();
    }

    private function exportarPlanejamento(\Illuminate\Support\Collection $aulas)
    {
        return response()->streamDownload(function () use ($aulas) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Data', 'Dia', 'Início', 'Fim', 'Disciplina', 'Professor', 'Sala', 'Frequência'], ';');
            foreach ($aulas as $a) {
                fputcsv($out, [
                    \Carbon\Carbon::parse($a['data'])->format('d/m/Y'), $a['dia'], $a['inicio'], $a['fim'],
                    $a['disciplina'], $a['professor'], $a['sala'], $a['frequencia_lancada'] ? 'Lançada' : 'Pendente',
                ], ';');
            }
            fclose($out);
        }, 'planejamento_' . now()->format('Ymd_His') . '.csv', ['Content-Type' => 'text/csv']);
    }

    /** Painel do Professor (257): notas e frequências por turma montada (com filtro por período). */
    public function painelProfessor(Request $request)
    {
        $turmasMontadas = TurmaMontada::with('turma')->orderByDesc('id')->get();
        $professores = Profissional::with('pessoa')->where('ativo', true)->get();
        $view = $request->get('view', 'notas'); // notas | frequencias
        $linhas = collect();
        $tm = null;
        $consultou = $request->filled('turma_montada_id');

        if ($consultou) {
            $tm = TurmaMontada::with('turma')->find($request->turma_montada_id);
            $inicio = $request->filled('inicio') ? $request->date('inicio') : null;
            $fim = $request->filled('fim') ? $request->date('fim') : null;

            $matriculas = Matricula::with('aluno.pessoa')->where('turma_montada_id', $tm->id)->get();

            $linhas = $matriculas->map(function ($m) use ($inicio, $fim) {
                $notas = Nota::where('matricula_id', $m->id)->whereNotNull('nota')->pluck('nota');
                $media = $notas->isNotEmpty() ? round($notas->avg(), 2) : null;

                $freqQuery = Frequencia::where('matricula_id', $m->id);
                if ($inicio) {
                    $freqQuery->whereDate('data', '>=', $inicio);
                }
                if ($fim) {
                    $freqQuery->whereDate('data', '<=', $fim);
                }
                $presencas = (clone $freqQuery)->whereIn('status', ['presente', 'justificada'])->count();
                $faltas = (clone $freqQuery)->where('status', 'ausente')->count();
                $total = $presencas + $faltas;
                $freq = $total > 0 ? round($presencas / $total * 100, 1) : null;

                return [
                    'aluno' => $m->aluno?->pessoa?->nome ?? '—',
                    'media' => $media,
                    'presencas' => $presencas,
                    'faltas' => $faltas,
                    'frequencia' => $freq,
                ];
            });
        }

        return view('academico.painel.painel-professor', compact('turmasMontadas', 'professores', 'linhas', 'tm', 'view', 'consultou', 'request'));
    }
}
