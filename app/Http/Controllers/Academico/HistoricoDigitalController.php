<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\HistoricoEscolar;
use App\Models\Integracao;
use App\Models\Matricula;
use App\Models\Nota;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Histórico Escolar Digital (226 do EDUQ): consolida o histórico do aluno
 * (lançamentos manuais + notas calculadas) num documento com bloco de assinatura
 * digital A1 e código de verificação. Sem certificado configurado em Integrações,
 * o documento sai como "pendente de assinatura".
 */
class HistoricoDigitalController extends Controller
{
    public function index()
    {
        $alunos = Aluno::with('pessoa')->whereHas('matriculas')->get()
            ->sortBy(fn ($a) => $a->pessoa?->nome)->values();
        $assinatura = Integracao::where('chave', 'assinatura_digital')->where('ativo', true)->first();

        return view('academico.historico-digital.index', compact('alunos', 'assinatura'));
    }

    public function gerar(Aluno $aluno)
    {
        $aluno->load('pessoa');
        $matriculas = Matricula::with(['turma.curso'])->where('aluno_id', $aluno->id)->get();

        // Notas calculadas por disciplina (todas as matrículas do aluno)
        $disciplinas = [];
        $notas = Nota::with(['disciplina', 'item'])
            ->whereIn('matricula_id', $matriculas->pluck('id'))->get()->groupBy('disciplina_id');
        foreach ($notas as $grupo) {
            $somaPonderada = $somaPesos = 0;
            foreach ($grupo as $nota) {
                if ($nota->nota === null) {
                    continue;
                }
                $peso = $nota->item?->peso ?? 1;
                $somaPonderada += $nota->nota * $peso;
                $somaPesos += $peso;
            }
            $disciplinas[] = [
                'nome' => $grupo->first()->disciplina?->nome ?? 'Disciplina',
                'media' => $somaPesos > 0 ? round($somaPonderada / $somaPesos, 2) : null,
                'situacao' => $grupo->first()->situacao,
                'origem' => 'Sistema',
            ];
        }

        // Histórico manual (alunos migrados de outra plataforma)
        $manuais = HistoricoEscolar::with(['disciplina', 'modulo'])
            ->whereIn('matricula_id', $matriculas->pluck('id'))->get();
        foreach ($manuais as $h) {
            $disciplinas[] = [
                'nome' => ($h->disciplina?->nome ?? 'Disciplina') . ($h->modulo ? ' (' . $h->modulo->nome . ')' : ''),
                'media' => $h->media,
                'situacao' => $h->status,
                'origem' => 'Histórico migrado',
            ];
        }

        $assinatura = Integracao::where('chave', 'assinatura_digital')->where('ativo', true)->first();
        $codigoVerificacao = strtoupper(substr(hash('sha256', $aluno->id . '|' . now()->format('YmdHi') . '|' . config('app.key')), 0, 16));

        $pdf = Pdf::loadView('academico.historico-digital.pdf', [
            'aluno' => $aluno,
            'matriculas' => $matriculas,
            'disciplinas' => $disciplinas,
            'assinatura' => $assinatura,
            'codigoVerificacao' => $codigoVerificacao,
            'emitidoEm' => now(),
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('historico_digital_' . $aluno->id . '.pdf');
    }
}
