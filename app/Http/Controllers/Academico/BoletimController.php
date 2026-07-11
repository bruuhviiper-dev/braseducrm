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
            $notas = Nota::with('item')
                ->where('matricula_id', $matricula->id)
                ->where('disciplina_id', $request->disciplina_id)
                ->whereNotNull('nota')
                ->get();

            // Modelo EDUQ (escolha do cliente): a média vem da avaliação da FÓRMULA da tabela
            // (ex.: (P1+P2)/2) sobre as SIGLAS das avaliações. Sem nota → sigla vale 0.
            $tabela = $notas->first()?->item?->tabela;
            $valores = [];
            foreach ($notas as $nota) {
                $sigla = $nota->item?->sigla;
                if ($sigla) {
                    $valores[strtoupper($sigla)] = (float) $nota->nota;
                }
            }
            $formula = $tabela?->formula;
            $media = null;
            if ($formula && $notas->isNotEmpty()) {
                $media = $this->avaliarFormula($formula, $valores);
                $media = $media !== null ? round($media, 2) : null;
            } elseif ($notas->isNotEmpty()) {
                // Sem fórmula cadastrada: cai para média simples das notas (fallback)
                $media = round($notas->avg('nota'), 2);
            }

            // Frequência %
            $totalAulas = Frequencia::where('matricula_id', $matricula->id)
                ->where('disciplina_id', $request->disciplina_id)->count();
            $presencas = Frequencia::where('matricula_id', $matricula->id)
                ->where('disciplina_id', $request->disciplina_id)
                ->whereIn('status', ['presente', 'justificada'])->count();
            $frequencia = $totalAulas > 0 ? round(($presencas / $totalAulas) * 100, 1) : null;

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
                'm1' => $media,
                'rec' => null,
                'rec_liberada' => false,
                'usou_rec' => false,
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

    /**
     * Avalia a fórmula da tabela (modelo EDUQ) substituindo as SIGLAS pelos valores
     * das notas do aluno e resolvendo a aritmética com segurança (sem eval do PHP).
     * Siglas ausentes valem 0. Aceita apenas + - * / ( ) e números.
     */
    private function avaliarFormula(string $formula, array $valores): ?float
    {
        $expr = $formula;
        // substitui as siglas mais longas primeiro (evita casar prefixos)
        $siglas = array_keys($valores);
        usort($siglas, fn ($a, $b) => strlen($b) <=> strlen($a));
        foreach ($siglas as $sigla) {
            $expr = preg_replace('/\b' . preg_quote($sigla, '/') . '\b/i', (string) $valores[$sigla], $expr);
        }
        // qualquer sigla que sobrou (sem nota) vira 0
        $expr = preg_replace('/[A-Za-z_][A-Za-z0-9_]*/', '0', $expr);
        // só permite dígitos, ponto, operadores e parênteses
        if (!preg_match('/^[0-9.+\-*\/() ]*$/', $expr) || trim($expr) === '') {
            return null;
        }
        try {
            $resultado = $this->resolverAritmetica($expr);
        } catch (\Throwable $e) {
            return null;
        }

        return is_finite($resultado) ? $resultado : null;
    }

    /** Avaliador aritmético seguro (shunting-yard) para + - * / e parênteses. */
    private function resolverAritmetica(string $expr): float
    {
        $tokens = preg_split('/\s+/', trim(preg_replace('/([+\-*\/()])/', ' $1 ', $expr)), -1, PREG_SPLIT_NO_EMPTY);
        $prec = ['+' => 1, '-' => 1, '*' => 2, '/' => 2];
        $saida = [];
        $ops = [];
        foreach ($tokens as $tk) {
            if (is_numeric($tk)) {
                $saida[] = (float) $tk;
            } elseif (isset($prec[$tk])) {
                while (!empty($ops) && end($ops) !== '(' && $prec[end($ops)] >= $prec[$tk]) {
                    $saida[] = array_pop($ops);
                }
                $ops[] = $tk;
            } elseif ($tk === '(') {
                $ops[] = $tk;
            } elseif ($tk === ')') {
                while (!empty($ops) && end($ops) !== '(') {
                    $saida[] = array_pop($ops);
                }
                array_pop($ops); // remove '('
            }
        }
        while (!empty($ops)) {
            $saida[] = array_pop($ops);
        }
        // avalia a notação polonesa reversa
        $pilha = [];
        foreach ($saida as $tk) {
            if (is_float($tk)) {
                $pilha[] = $tk;
            } else {
                $b = array_pop($pilha);
                $a = array_pop($pilha);
                $pilha[] = match ($tk) {
                    '+' => $a + $b,
                    '-' => $a - $b,
                    '*' => $a * $b,
                    '/' => $b != 0.0 ? $a / $b : 0.0,
                };
            }
        }

        return (float) (end($pilha) ?: 0);
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
