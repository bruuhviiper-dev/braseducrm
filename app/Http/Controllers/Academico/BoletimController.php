<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\TurmaMontada;
use App\Models\Disciplina;
use App\Models\Matricula;
use App\Models\Nota;
use App\Models\Frequencia;
use App\Models\ConfiguracaoBoletim;
use Illuminate\Http\Request;

class BoletimController extends Controller
{
    public function index(Request $request)
    {
        $turmasMontadas = TurmaMontada::with('turma')->orderBy('id', 'desc')->get();
        $disciplinas = Disciplina::where('ativo', true)->orderBy('nome')->get();
        $configuracoes = ConfiguracaoBoletim::orderBy('nome')->get();

        $resultado = null;
        if ($request->filled(['turma_montada_id', 'disciplina_id'])) {
            $resultado = $this->calcular($request);
        }

        return view('academico.boletim.index', compact('turmasMontadas', 'disciplinas', 'configuracoes', 'resultado', 'request'));
    }

    private function calcular(Request $request): array
    {
        $config = $request->filled('configuracao_boletim_id')
            ? ConfiguracaoBoletim::find($request->configuracao_boletim_id)
            : null;

        $mediaAprovacao = $config?->media_aprovacao ?? 7.0;
        $frequenciaMinima = $config?->frequencia_minima ?? 75.0;

        $matriculas = Matricula::with('aluno.pessoa')
            ->where('turma_montada_id', $request->turma_montada_id)
            ->whereIn('situacao', ['ativa', 'concluida'])
            ->get();

        $linhas = [];
        foreach ($matriculas as $matricula) {
            // Média ponderada das notas pelos pesos dos itens
            $notas = Nota::with('item')
                ->where('matricula_id', $matricula->id)
                ->where('disciplina_id', $request->disciplina_id)
                ->whereNotNull('nota')
                ->get();

            $somaPonderada = 0;
            $somaPesos = 0;
            foreach ($notas as $nota) {
                $peso = $nota->item?->peso ?? 1;
                $somaPonderada += $nota->nota * $peso;
                $somaPesos += $peso;
            }
            $media = $somaPesos > 0 ? round($somaPonderada / $somaPesos, 2) : null;

            // Frequência %
            $totalAulas = Frequencia::where('matricula_id', $matricula->id)
                ->where('disciplina_id', $request->disciplina_id)->count();
            $presencas = Frequencia::where('matricula_id', $matricula->id)
                ->where('disciplina_id', $request->disciplina_id)
                ->whereIn('status', ['presente', 'justificada'])->count();
            $frequencia = $totalAulas > 0 ? round(($presencas / $totalAulas) * 100, 1) : null;

            // Situação
            $situacao = 'cursando';
            if ($media !== null) {
                $aprovadoNota = $media >= $mediaAprovacao;
                $aprovadoFreq = $frequencia === null || $frequencia >= $frequenciaMinima;
                if ($aprovadoNota && $aprovadoFreq) {
                    $situacao = 'aprovado';
                } elseif (!$aprovadoFreq) {
                    $situacao = 'reprovado_falta';
                } else {
                    $situacao = 'reprovado';
                }
            }

            $linhas[] = [
                'matricula' => $matricula,
                'media' => $media,
                'frequencia' => $frequencia,
                'total_aulas' => $totalAulas,
                'presencas' => $presencas,
                'situacao' => $situacao,
            ];
        }

        return [
            'linhas' => $linhas,
            'media_aprovacao' => $mediaAprovacao,
            'frequencia_minima' => $frequenciaMinima,
        ];
    }

    public function consolidar(Request $request)
    {
        $request->validate([
            'turma_montada_id' => 'required|exists:turmas_montadas,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
        ]);

        $resultado = $this->calcular($request);
        $mapa = ['aprovado' => 'aprovado', 'reprovado' => 'reprovado', 'reprovado_falta' => 'reprovado', 'cursando' => 'cursando'];

        foreach ($resultado['linhas'] as $linha) {
            if ($linha['media'] === null) {
                continue;
            }
            Nota::where('matricula_id', $linha['matricula']->id)
                ->where('disciplina_id', $request->disciplina_id)
                ->update(['situacao' => $mapa[$linha['situacao']] ?? 'cursando']);
        }

        return redirect()->route('academico.boletim.index', $request->only(['turma_montada_id', 'disciplina_id', 'configuracao_boletim_id']))
            ->with('success', 'Boletim consolidado: situação dos alunos atualizada.');
    }
}
