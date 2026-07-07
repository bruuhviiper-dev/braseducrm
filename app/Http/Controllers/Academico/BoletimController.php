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

        $resultado = null;
        if ($request->filled(['turma_montada_id', 'disciplina_id'])) {
            $resultado = $this->calcular($request);
        }

        return view('academico.boletim.index', compact('turmasMontadas', 'disciplinas', 'resultado', 'request'));
    }

    private function calcular(Request $request): array
    {
        // Config do boletim deriva da matriz curricular da turma (EDUQ: definida na matriz, não escolhida aqui)
        $tm = TurmaMontada::with('turma.matrizCurricular')->find($request->turma_montada_id);
        $configId = $tm?->turma?->matrizCurricular?->configuracao_boletim_id;
        $config = $configId ? ConfiguracaoBoletim::find($configId) : null;

        $mediaAprovacao = $config?->media_aprovacao ?? 7.0;
        $frequenciaMinima = $config?->frequencia_minima ?? 75.0;

        // Modelo de recuperação dos docs do EDUQ: direto (Cursos Livres, MF = M1),
        // recuperacao_media (Graduação, MF = (M1 + REC) / 2) ou recuperacao_substitui (Pós, MF = REC)
        $modelo = $config?->modelo ?? 'direto';
        $recMin = (float) ($config?->rec_min ?? 0);
        $recMax = (float) ($config?->rec_max ?? 5.99);
        $mediaAprovacaoFinal = (float) ($config?->media_aprovacao_final ?? $mediaAprovacao);

        $matriculas = Matricula::with('aluno.pessoa')
            ->where('turma_montada_id', $request->turma_montada_id)
            ->whereIn('situacao', ['ativa', 'confirmada', 'nao_confirmada', 'concluida'])
            ->get();

        $linhas = [];
        foreach ($matriculas as $matricula) {
            // Média ponderada das notas pelos pesos dos itens
            $notas = Nota::with('item')
                ->where('matricula_id', $matricula->id)
                ->where('disciplina_id', $request->disciplina_id)
                ->whereNotNull('nota')
                ->get();

            // M1 = média ponderada dos itens comuns; a nota do item marcado como REC fica de fora
            $somaPonderada = 0;
            $somaPesos = 0;
            $rec = null;
            foreach ($notas as $nota) {
                if ($nota->item?->recuperacao) {
                    $rec = (float) $nota->nota;
                    continue;
                }
                $peso = $nota->item?->peso ?? 1;
                $somaPonderada += $nota->nota * $peso;
                $somaPesos += $peso;
            }
            $m1 = $somaPesos > 0 ? round($somaPonderada / $somaPesos, 2) : null;

            // REC só é liberada se a M1 cair na faixa configurada (ex.: 0 a 5,99)
            $recLiberada = $modelo !== 'direto' && $m1 !== null && $m1 >= $recMin && $m1 <= $recMax;

            // Média Final conforme o modelo; REC nula (aluno passou direto) mantém a M1
            $media = $m1;
            $usouRec = false;
            if ($recLiberada && $rec !== null) {
                $media = $modelo === 'recuperacao_substitui'
                    ? round($rec, 2)
                    : round(($m1 + $rec) / 2, 2); // recuperacao_media
                $usouRec = true;
            }

            // Frequência %
            $totalAulas = Frequencia::where('matricula_id', $matricula->id)
                ->where('disciplina_id', $request->disciplina_id)->count();
            $presencas = Frequencia::where('matricula_id', $matricula->id)
                ->where('disciplina_id', $request->disciplina_id)
                ->whereIn('status', ['presente', 'justificada'])->count();
            $frequencia = $totalAulas > 0 ? round(($presencas / $totalAulas) * 100, 1) : null;

            // Situação: quem foi para a REC é aprovado pela média pós-REC (ex.: 5), os demais pela média normal
            $situacao = 'cursando';
            if ($media !== null) {
                $corte = $usouRec ? $mediaAprovacaoFinal : $mediaAprovacao;
                $aprovadoNota = $media >= $corte;
                $aprovadoFreq = $frequencia === null || $frequencia >= $frequenciaMinima;
                if ($recLiberada && $rec === null && !($m1 >= $mediaAprovacao)) {
                    $situacao = 'em_recuperacao'; // aguardando a nota da REC
                } elseif ($aprovadoNota && $aprovadoFreq) {
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
                'm1' => $m1,
                'rec' => $rec,
                'rec_liberada' => $recLiberada,
                'usou_rec' => $usouRec,
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
            'modelo' => $modelo,
            'rec_min' => $recMin,
            'rec_max' => $recMax,
            'media_aprovacao_final' => $mediaAprovacaoFinal,
        ];
    }

    /** "Processar" (EDUQ): calcula o boletim; se o toggle "resultado final" estiver ligado, consolida a situação. */
    public function consolidar(Request $request)
    {
        $request->validate([
            'turma_montada_id' => 'required|exists:turmas_montadas,id',
            'disciplina_id' => 'required|exists:disciplinas,id',
            'calcular_final' => 'nullable',
        ]);

        $filtros = $request->only(['turma_montada_id', 'disciplina_id']);

        if (! $request->boolean('calcular_final')) {
            // Apenas processa a prévia (sem gravar resultado final)
            return redirect()->route('academico.boletim.index', $filtros)
                ->with('success', 'Boletim processado.');
        }

        $resultado = $this->calcular($request);
        $mapa = ['aprovado' => 'aprovado', 'reprovado' => 'reprovado', 'reprovado_falta' => 'reprovado', 'cursando' => 'cursando', 'em_recuperacao' => 'cursando'];

        foreach ($resultado['linhas'] as $linha) {
            if ($linha['media'] === null) {
                continue;
            }
            Nota::where('matricula_id', $linha['matricula']->id)
                ->where('disciplina_id', $request->disciplina_id)
                ->update(['situacao' => $mapa[$linha['situacao']] ?? 'cursando']);
        }

        return redirect()->route('academico.boletim.index', $filtros)
            ->with('success', 'Resultado final calculado: situação dos alunos atualizada.');
    }
}
