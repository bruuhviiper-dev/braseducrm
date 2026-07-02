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

    /** Planejamento Diário de Aulas (45): horários montados de uma turma. */
    public function planejamentoDiario(Request $request)
    {
        $turmasMontadas = TurmaMontada::with('turma')->orderByDesc('id')->get();
        $aulas = collect();
        $tm = null;

        if ($request->filled('turma_montada_id')) {
            $tm = TurmaMontada::with('turma')->find($request->turma_montada_id);
            $aulas = Horario::with(['disciplina', 'profissional.pessoa', 'sala'])
                ->where('turma_montada_id', $request->turma_montada_id)
                ->orderBy('dia_semana')->orderBy('hora_inicio')->get()
                ->map(fn ($h) => [
                    'dia' => self::DIAS[$h->dia_semana] ?? $h->dia_semana,
                    'inicio' => substr($h->hora_inicio, 0, 5),
                    'fim' => substr($h->hora_fim, 0, 5),
                    'disciplina' => $h->disciplina?->nome ?? '—',
                    'professor' => $h->profissional?->pessoa?->nome ?? '—',
                    'sala' => $h->sala?->nome ?? '—',
                ]);
        }

        return view('academico.painel.planejamento-diario', compact('turmasMontadas', 'aulas', 'tm'));
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
