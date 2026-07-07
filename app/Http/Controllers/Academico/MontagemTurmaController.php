<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\TurmaMontada;
use App\Models\Turma;
use App\Models\Modulo;
use App\Models\PeriodoLetivo;
use App\Models\Disciplina;
use App\Models\Profissional;
use App\Models\Sala;
use App\Models\Aluno;
use App\Models\Matricula;
use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MontagemTurmaController extends Controller
{
    public function index()
    {
        $turmasMontadas = TurmaMontada::with(['turma', 'modulo', 'periodoLetivo'])
            ->withCount('horarios')
            ->orderBy('id', 'desc')->paginate(20);
        return view('academico.montagem-turma.index', compact('turmasMontadas'));
    }

    public function create()
    {
        return view('academico.montagem-turma.form', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        $turmaMontada = TurmaMontada::create([
            'turma_id' => $validated['turma_id'],
            'modulo_id' => $validated['modulo_id'] ?? null,
            'periodo_letivo_id' => $validated['periodo_letivo_id'] ?? null,
            'sigla' => $validated['sigla'] ?? null,
            'nome' => $validated['nome'] ?? null,
            'situacao' => $validated['situacao'],
            'data_inicio' => $validated['data_inicio'] ?? null,
            'data_fim' => $validated['data_fim'] ?? null,
            'ativo' => $request->boolean('ativo'),
        ]);
        $this->syncHorarios($turmaMontada, $validated['horarios'] ?? []);
        return redirect()->route('academico.montagem-turma.edit', $turmaMontada)
            ->with('success', 'Turma montada criada. Agora matricule os alunos abaixo.');
    }

    public function edit(TurmaMontada $montagem_turma)
    {
        $turmaMontada = $montagem_turma->load(['horarios.disciplina', 'horarios.profissional.pessoa', 'horarios.sala']);
        $matriculados = Matricula::with('aluno.pessoa')
            ->where('turma_montada_id', $turmaMontada->id)->get();
        $alunosDisponiveis = Aluno::with('pessoa')->where('ativo', true)->get();

        return view('academico.montagem-turma.form', array_merge($this->formData(), compact('turmaMontada', 'matriculados', 'alunosDisponiveis')));
    }

    public function update(Request $request, TurmaMontada $montagem_turma)
    {
        $validated = $this->validateData($request);
        $montagem_turma->update([
            'turma_id' => $validated['turma_id'],
            'modulo_id' => $validated['modulo_id'] ?? null,
            'periodo_letivo_id' => $validated['periodo_letivo_id'] ?? null,
            'sigla' => $validated['sigla'] ?? null,
            'nome' => $validated['nome'] ?? null,
            'situacao' => $validated['situacao'],
            'data_inicio' => $validated['data_inicio'] ?? null,
            'data_fim' => $validated['data_fim'] ?? null,
            'ativo' => $request->boolean('ativo'),
        ]);
        $this->syncHorarios($montagem_turma, $validated['horarios'] ?? []);
        return redirect()->route('academico.montagem-turma.edit', $montagem_turma)
            ->with('success', 'Turma montada atualizada com sucesso.');
    }

    public function destroy(TurmaMontada $montagem_turma)
    {
        $montagem_turma->horarios()->delete();
        $montagem_turma->delete();
        return redirect()->route('academico.montagem-turma.index')->with('success', 'Turma montada removida.');
    }

    /** Matricula um aluno na turma montada. */
    public function matricular(Request $request, TurmaMontada $montagem_turma)
    {
        $data = $request->validate([
            'aluno_id' => 'required|exists:alunos,id',
        ]);

        $jaMatriculado = Matricula::where('turma_montada_id', $montagem_turma->id)
            ->where('aluno_id', $data['aluno_id'])->exists();
        if ($jaMatriculado) {
            return back()->with('error', 'Este aluno já está matriculado nesta turma montada.');
        }

        Matricula::create([
            'aluno_id' => $data['aluno_id'],
            'turma_id' => $montagem_turma->turma_id,
            'turma_montada_id' => $montagem_turma->id,
            'numero_matricula' => $this->gerarNumeroMatricula($montagem_turma),
            'data_matricula' => now(),
            // EDUQ: a matrícula nasce "Não Confirmada" e só é confirmada após a validação do pagamento da taxa
            'situacao' => 'nao_confirmada',
        ]);

        return back()->with('success', 'Aluno matriculado (Não Confirmada). Confirme após validar o pagamento da taxa de inscrição.');
    }

    /** EDUQ: transição para "Confirmada" após a secretaria validar o pagamento da taxa de inscrição. */
    public function confirmar(TurmaMontada $montagem_turma, Matricula $matricula)
    {
        $matricula->update(['situacao' => 'confirmada']);
        return back()->with('success', 'Matrícula confirmada.');
    }

    /**
     * Máscara de matrícula do EDUQ (Ecrã 167): [Ano de ingresso] + [Sigla da turma montada] + [chave única],
     * evitando duplicidade quando dois alunos entram simultaneamente.
     */
    private function gerarNumeroMatricula(TurmaMontada $tm): string
    {
        $sigla = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) ($tm->sigla ?: 'TM' . $tm->id)));
        do {
            $numero = date('Y') . $sigla . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
        } while (Matricula::where('numero_matricula', $numero)->exists());

        return $numero;
    }

    public function desmatricular(TurmaMontada $montagem_turma, Matricula $matricula)
    {
        $matricula->delete();
        return back()->with('success', 'Aluno removido da turma montada.');
    }

    /** Finalizar Turma (EDUQ): prévia com média/frequência de cada aluno e proposta de aprovação. */
    public function finalizar(TurmaMontada $montagem_turma)
    {
        $linhas = $this->apurarResultados($montagem_turma);
        $turmasDestino = TurmaMontada::with('turma')
            ->where('id', '!=', $montagem_turma->id)
            ->where('situacao', '!=', 'finalizada')
            ->orderByDesc('id')->get();

        return view('academico.montagem-turma.finalizar', [
            'turmaMontada' => $montagem_turma->load('turma.matrizCurricular'),
            'linhas' => $linhas,
            'turmasDestino' => $turmasDestino,
        ]);
    }

    /** Processa o encerramento em lote: aprova quem atingiu média + frequência e aplica o destino dos retidos. */
    public function processarFinalizacao(Request $request, TurmaMontada $montagem_turma)
    {
        $data = $request->validate([
            'destinos' => 'nullable|array',
            'destinos.*' => 'nullable|in:trancada,desistente,cancelada,dependencia,recuperacao',
            'turma_destino_id' => 'nullable|exists:turmas_montadas,id',
        ]);

        $linhas = $this->apurarResultados($montagem_turma);
        $turmaDestino = !empty($data['turma_destino_id']) ? TurmaMontada::find($data['turma_destino_id']) : null;
        $aprovados = 0;
        $retidos = 0;

        DB::transaction(function () use ($linhas, $data, $turmaDestino, &$aprovados, &$retidos) {
            foreach ($linhas as $linha) {
                $matricula = $linha['matricula'];
                if ($linha['aprovado']) {
                    $matricula->update(['situacao' => 'concluida']);
                    $aprovados++;
                    // Ação coletiva de renovação: matricula o aprovado na turma do módulo seguinte
                    if ($turmaDestino && !Matricula::where('turma_montada_id', $turmaDestino->id)->where('aluno_id', $matricula->aluno_id)->exists()) {
                        Matricula::create([
                            'aluno_id' => $matricula->aluno_id,
                            'turma_id' => $turmaDestino->turma_id,
                            'turma_montada_id' => $turmaDestino->id,
                            'numero_matricula' => $this->gerarNumeroMatricula($turmaDestino),
                            'data_matricula' => now(),
                            'situacao' => $linha['inadimplente'] ? 'nao_confirmada' : 'confirmada',
                        ]);
                    }
                } else {
                    $destino = $data['destinos'][$matricula->id] ?? 'dependencia';
                    $matricula->update(['situacao' => $destino === 'recuperacao' ? 'dependencia' : $destino]);
                    $retidos++;
                }
            }
        });

        $montagem_turma->update(['situacao' => 'finalizada']);

        return redirect()->route('academico.montagem-turma.index')
            ->with('success', "Turma finalizada: {$aprovados} aluno(s) aprovado(s)" . ($retidos ? ", {$retidos} retido(s)" : '') . ($request->filled('turma_destino_id') ? '. Aprovados matriculados na turma seguinte (inadimplentes ficam Não Confirmados).' : '.'));
    }

    /** Apura média geral e frequência de cada aluno da turma montada (regra: média e frequência mínimas da matriz/boletim). */
    private function apurarResultados(TurmaMontada $tm): array
    {
        $tm->load('turma.matrizCurricular');
        $config = $tm->turma?->matrizCurricular?->configuracao_boletim_id
            ? \App\Models\ConfiguracaoBoletim::find($tm->turma->matrizCurricular->configuracao_boletim_id)
            : null;
        $mediaAprovacao = (float) ($config->media_aprovacao ?? 6.0);
        $frequenciaMinima = (float) ($tm->turma?->matrizCurricular?->percentual_frequencia ?? $config->frequencia_minima ?? 75.0);

        $matriculas = Matricula::with('aluno.pessoa')
            ->where('turma_montada_id', $tm->id)
            ->whereIn('situacao', ['ativa', 'confirmada', 'nao_confirmada'])
            ->get();

        $linhas = [];
        foreach ($matriculas as $matricula) {
            $notas = \App\Models\Nota::where('matricula_id', $matricula->id)->whereNotNull('nota')->get();
            $media = $notas->count() ? round($notas->avg('nota'), 2) : null;

            $totalAulas = \App\Models\Frequencia::where('matricula_id', $matricula->id)->count();
            $presencas = \App\Models\Frequencia::where('matricula_id', $matricula->id)
                ->whereIn('status', ['presente', 'justificada'])->count();
            $frequencia = $totalAulas > 0 ? round(($presencas / $totalAulas) * 100, 1) : null;

            // Filtro de inadimplência: barra a renovação automática (matrícula seguinte entra "Não Confirmada")
            $inadimplente = \App\Models\TituloReceber::where('matricula_id', $matricula->id)
                ->where('situacao', 'aberto')
                ->where('data_vencimento', '<', now())
                ->exists();

            $aprovado = ($media === null || $media >= $mediaAprovacao)
                && ($frequencia === null || $frequencia >= $frequenciaMinima)
                && ($media !== null || $frequencia !== null); // sem nenhum dado, não aprova automaticamente

            $linhas[] = [
                'matricula' => $matricula,
                'media' => $media,
                'frequencia' => $frequencia,
                'inadimplente' => $inadimplente,
                'aprovado' => $aprovado,
                'media_aprovacao' => $mediaAprovacao,
                'frequencia_minima' => $frequenciaMinima,
            ];
        }

        return $linhas;
    }

    private function formData(): array
    {
        return [
            'turmas' => Turma::orderBy('nome')->get(),
            'modulos' => Modulo::orderBy('nome')->get(),
            'periodos' => PeriodoLetivo::orderBy('id', 'desc')->get(),
            'disciplinas' => Disciplina::where('ativo', true)->orderBy('nome')->get(),
            'profissionais' => Profissional::with('pessoa')->where('ativo', true)->get(),
            'salas' => Sala::where('ativo', true)->orderBy('nome')->get(),
            'diasSemana' => Horario::diasSemana(),
        ];
    }

    private function syncHorarios(TurmaMontada $turmaMontada, array $horarios): void
    {
        $turmaMontada->horarios()->delete();
        foreach ($horarios as $h) {
            if (empty($h['disciplina_id'])) {
                continue;
            }
            $turmaMontada->horarios()->create([
                'disciplina_id' => $h['disciplina_id'],
                'profissional_id' => $h['profissional_id'] ?? null,
                'sala_id' => $h['sala_id'] ?? null,
                'dia_semana' => $h['dia_semana'],
                'hora_inicio' => $h['hora_inicio'],
                'hora_fim' => $h['hora_fim'],
            ]);
        }
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'turma_id' => 'required|exists:turmas,id',
            'modulo_id' => 'nullable|exists:modulos,id',
            'periodo_letivo_id' => 'nullable|exists:periodos_letivos,id',
            'sigla' => 'nullable|string|max:60',
            'nome' => 'nullable|string|max:255',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'ativo' => 'nullable',
            'situacao' => 'required|in:aberta,em_andamento,finalizada',
            'horarios' => 'nullable|array',
            'horarios.*.disciplina_id' => 'nullable|exists:disciplinas,id',
            'horarios.*.profissional_id' => 'nullable|exists:profissionais,id',
            'horarios.*.sala_id' => 'nullable|exists:salas,id',
            'horarios.*.dia_semana' => 'required_with:horarios.*.disciplina_id|integer|between:1,7',
            'horarios.*.hora_inicio' => 'required_with:horarios.*.disciplina_id',
            'horarios.*.hora_fim' => 'required_with:horarios.*.disciplina_id',
        ]);
    }
}
