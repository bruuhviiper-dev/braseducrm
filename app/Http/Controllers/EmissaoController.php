<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Matricula;
use App\Models\Nota;
use App\Models\TituloReceber;
use Barryvdh\DomPDF\Facade\Pdf;

class EmissaoController extends Controller
{
    /**
     * Histórico escolar do aluno: notas por disciplina com média ponderada e situação.
     */
    public function historicoEscolar(Aluno $aluno)
    {
        $aluno->load('pessoa');

        $matriculas = Matricula::with(['turma.curso'])
            ->where('aluno_id', $aluno->id)->get();

        // Agrupa notas por disciplina considerando todas as matrículas do aluno.
        $notas = Nota::with(['disciplina', 'item'])
            ->whereIn('matricula_id', $matriculas->pluck('id'))
            ->get()
            ->groupBy('disciplina_id');

        $disciplinas = [];
        foreach ($notas as $disciplinaId => $grupo) {
            $somaPonderada = 0;
            $somaPesos = 0;
            foreach ($grupo as $nota) {
                if ($nota->nota === null) {
                    continue;
                }
                $peso = $nota->item?->peso ?? 1;
                $somaPonderada += $nota->nota * $peso;
                $somaPesos += $peso;
            }
            $media = $somaPesos > 0 ? round($somaPonderada / $somaPesos, 2) : null;
            $disciplinas[] = [
                'nome' => $grupo->first()->disciplina?->nome ?? 'Disciplina',
                'media' => $media,
                'situacao' => $grupo->first()->situacao,
            ];
        }

        $pdf = Pdf::loadView('emissoes.historico', compact('aluno', 'matriculas', 'disciplinas'));
        return $pdf->stream('historico_' . $aluno->id . '.pdf');
    }

    /**
     * Declaração de matrícula.
     */
    public function declaracaoMatricula(Matricula $matricula)
    {
        $matricula->load(['aluno.pessoa', 'turma.curso']);
        $pdf = Pdf::loadView('emissoes.declaracao', compact('matricula'));
        return $pdf->stream('declaracao_' . $matricula->id . '.pdf');
    }

    /**
     * Recibo de pagamento de um título quitado.
     */
    public function recibo(TituloReceber $titulo)
    {
        $titulo->load('pessoa');
        $pdf = Pdf::loadView('emissoes.recibo', compact('titulo'));
        return $pdf->stream('recibo_' . $titulo->id . '.pdf');
    }
}
