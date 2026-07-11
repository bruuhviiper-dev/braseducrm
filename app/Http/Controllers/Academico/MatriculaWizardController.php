<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\CategoriaReceber;
use App\Models\Enturmacao;
use App\Models\FormaIngresso;
use App\Models\HistoricoOportunidade;
use App\Models\Matricula;
use App\Models\MotivoGanho;
use App\Models\MovimentacaoMatricula;
use App\Models\Oportunidade;
use App\Models\PlanoContas;
use App\Models\TituloReceber;
use App\Models\TurmaMontada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Assistente "Matrícula e Histórico" em 4 passos (doc CRM): Informações básicas →
 * Disciplinas iniciais (herdadas da matriz curricular da turma) → Financeiro
 * (taxa de matrícula + carnê de mensalidades) → Conferência. Aberto ao dar
 * GANHO no card do funil — é o handoff do Comercial para a Secretaria.
 */
class MatriculaWizardController extends Controller
{
    public function create(Request $request)
    {
        $oportunidade = $request->query('oportunidade')
            ? Oportunidade::with(['interessado', 'origem', 'curso'])->find($request->query('oportunidade'))
            : null;

        $turmas = TurmaMontada::with('turma.matrizCurricular.disciplinas')
            ->where('ativo', true)->orderBy('nome')->get();

        // Herança da Matriz Curricular (doc): mapa turma montada → disciplinas da grade
        $turmaDisciplinas = $turmas->mapWithKeys(function ($tm) {
            $discs = $tm->turma?->matrizCurricular?->disciplinas ?? collect();

            return [$tm->id => $discs->map(fn ($d) => [
                'id' => $d->id,
                'nome' => ($d->sigla ? $d->sigla . ' - ' : '') . $d->nome,
            ])->values()];
        });

        $alunos = Aluno::with('pessoa')->where('ativo', true)->get()
            ->sortBy(fn ($a) => $a->pessoa->nome ?? '')->values();

        // Pré-seleção herdada do card do CRM (doc: rastreabilidade comercial)
        $alunoSugerido = null;
        if ($oportunidade?->interessado) {
            $alunoSugerido = $alunos->first(fn ($a) => mb_strtolower($a->pessoa->nome ?? '') === mb_strtolower($oportunidade->interessado->nome ?? ''));
        }
        $turmaSugerida = $oportunidade?->curso_id
            ? $turmas->first(fn ($tm) => $tm->turma?->curso_id == $oportunidade->curso_id)
            : null;

        $proximoSeq = 8000 + ((int) Matricula::max('id')) + 1;

        return view('academico.matriculas.wizard', [
            'oportunidade' => $oportunidade,
            'alunos' => $alunos,
            'turmas' => $turmas,
            'turmaDisciplinas' => $turmaDisciplinas,
            'formasIngresso' => FormaIngresso::orderBy('nome')->get(),
            'motivosGanho' => MotivoGanho::orderBy('nome')->get(),
            'categorias' => CategoriaReceber::orderBy('nome')->get(),
            'planosConta' => PlanoContas::where('ativo', true)->orderBy('codigo')->get(),
            'tags' => \App\Models\TagCrm::orderBy('nome')->get(),
            'alunoSugerido' => $alunoSugerido,
            'turmaSugerida' => $turmaSugerida,
            'proximoSeq' => $proximoSeq,
        ]);
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'oportunidade_id' => 'nullable|exists:oportunidades,id',
            // Passo 1
            'aluno_id' => 'required|exists:alunos,id',
            'turma_montada_id' => 'required|exists:turmas_montadas,id',
            'forma_ingresso_id' => 'required|exists:formas_ingresso,id',
            'como_conheceu' => 'nullable|string|max:255',
            'data_inicio_aulas' => 'required|date',
            'tag' => 'nullable|string|max:255',
            'motivo_ganho_id' => 'nullable|exists:motivos_ganho,id',
            'solucao_personalizada' => 'nullable|string|max:255',
            'observacoes' => 'nullable|string|max:2000',
            // Passo 2
            'disciplinas' => 'nullable|array',
            'disciplinas.*' => 'integer|exists:disciplinas,id',
            // Passo 3 — Matrícula (taxa de adesão)
            'gerar_matricula' => 'nullable|boolean',
            'mat_valor' => 'required_if:gerar_matricula,1|nullable|numeric|min:0',
            'mat_vencimento' => 'required_if:gerar_matricula,1|nullable|date',
            'mat_categoria_id' => 'required_if:gerar_matricula,1|nullable|exists:categorias_receber,id',
            'mat_forma_pagamento' => 'required_if:gerar_matricula,1|nullable|in:boleto,cartao,dinheiro,pix,cheque,transferencia',
            'mat_plano_conta_id' => 'required_if:gerar_matricula,1|nullable|exists:plano_contas,id',
            'mat_juros' => 'nullable|boolean',
            'mat_desconto' => 'nullable|numeric|min:0',
            // Passo 3 — Mensalidade (carnê recorrente)
            'gerar_mensalidade' => 'nullable|boolean',
            'men_valor' => 'required_if:gerar_mensalidade,1|nullable|numeric|min:0',
            'men_parcelas' => 'required_if:gerar_mensalidade,1|nullable|integer|min:1|max:72',
            'men_primeira' => 'required_if:gerar_mensalidade,1|nullable|date',
            'men_categoria_id' => 'required_if:gerar_mensalidade,1|nullable|exists:categorias_receber,id',
            'men_forma_pagamento' => 'required_if:gerar_mensalidade,1|nullable|in:boleto,cartao,dinheiro,pix,cheque,transferencia',
            'men_plano_conta_id' => 'required_if:gerar_mensalidade,1|nullable|exists:plano_contas,id',
            'men_instrucoes' => 'nullable|string|max:250',
            'men_juros' => 'nullable|boolean',
            'men_desconto' => 'nullable|numeric|min:0',
        ], [
            'required_if' => 'Campo obrigatório para gerar a cobrança.',
        ]);

        $matricula = DB::transaction(function () use ($v, $request) {
            $turmaMontada = TurmaMontada::with('turma')->findOrFail($v['turma_montada_id']);
            $aluno = Aluno::with('pessoa')->findOrFail($v['aluno_id']);

            // Identificador estruturado (doc: ano civil + código da turma + sequencial)
            $identificador = now()->year . '.' . ($turmaMontada->sigla ?: $turmaMontada->nome) . '.' . (8000 + ((int) Matricula::max('id')) + 1);

            $parcelas = (int) ($v['men_parcelas'] ?? 0);
            $matricula = Matricula::create([
                'aluno_id' => $aluno->id,
                'turma_id' => $turmaMontada->turma_id,
                'turma_montada_id' => $turmaMontada->id,
                'matriz_curricular_id' => $turmaMontada->turma?->matriz_curricular_id,
                'numero_matricula' => $identificador,
                'data_matricula' => now()->toDateString(),
                'data_inicio_aulas' => $v['data_inicio_aulas'],
                'situacao' => 'ativa',
                'forma_ingresso_id' => $v['forma_ingresso_id'],
                'como_conheceu' => $v['como_conheceu'] ?? null,
                'consultor_id' => auth()->id(),
                'tag' => $v['tag'] ?? null,
                'solucao_personalizada' => $v['solucao_personalizada'] ?? null,
                'observacoes' => $v['observacoes'] ?? null,
                'valor_total' => $request->boolean('gerar_mensalidade') ? round(((float) $v['men_valor']) * $parcelas, 2) : null,
                'num_parcelas' => $request->boolean('gerar_mensalidade') ? $parcelas : null,
                'valor_parcela' => $request->boolean('gerar_mensalidade') ? $v['men_valor'] : null,
                'primeiro_vencimento' => $v['men_primeira'] ?? null,
            ]);

            // Passo 2: pauta acadêmica — enturmação nas disciplinas mantidas na conferência
            foreach ($v['disciplinas'] ?? [] as $disciplinaId) {
                Enturmacao::create([
                    'matricula_id' => $matricula->id,
                    'disciplina_id' => $disciplinaId,
                    'turma_montada_id' => $turmaMontada->id,
                    'data_inicio' => $v['data_inicio_aulas'],
                    'tipo' => 'normal',
                ]);
            }

            MovimentacaoMatricula::registrar($matricula->id,
                'Matrícula criada pelo assistente em 4 passos (identificador ' . $identificador . ', ' . count($v['disciplinas'] ?? []) . ' disciplinas iniciais).',
                'ativa', $v['tag'] ?? null);

            // Passo 3: bloco Matrícula (taxa de adesão)
            if ($request->boolean('gerar_matricula')) {
                TituloReceber::create([
                    'pessoa_id' => $aluno->pessoa_id,
                    'matricula_id' => $matricula->id,
                    'categoria_receber_id' => $v['mat_categoria_id'],
                    'plano_conta_id' => $v['mat_plano_conta_id'],
                    'numero_documento' => $identificador . '-MAT',
                    'valor_original' => $v['mat_valor'],
                    'valor_desconto' => $v['mat_desconto'] ?? 0,
                    'data_emissao' => now()->toDateString(),
                    'data_vencimento' => $v['mat_vencimento'],
                    'situacao' => 'aberto',
                    'forma_pagamento' => $v['mat_forma_pagamento'],
                    'cobrar_juros_multa' => $request->boolean('mat_juros'),
                    'gerado_por' => auth()->id(),
                ]);
            }

            // Passo 3: carnê de mensalidades — projeta as demais parcelas pelo dia da 1ª
            if ($request->boolean('gerar_mensalidade')) {
                $primeira = \Carbon\Carbon::parse($v['men_primeira']);
                for ($i = 1; $i <= $parcelas; $i++) {
                    TituloReceber::create([
                        'pessoa_id' => $aluno->pessoa_id,
                        'matricula_id' => $matricula->id,
                        'categoria_receber_id' => $v['men_categoria_id'],
                        'plano_conta_id' => $v['men_plano_conta_id'],
                        'numero_documento' => $identificador . '-' . $i . '/' . $parcelas,
                        'valor_original' => $v['men_valor'],
                        'valor_desconto' => $v['men_desconto'] ?? 0,
                        'data_emissao' => now()->toDateString(),
                        'data_vencimento' => $primeira->copy()->addMonthsNoOverflow($i - 1)->toDateString(),
                        'situacao' => 'aberto',
                        'forma_pagamento' => $v['men_forma_pagamento'],
                        'instrucoes_boleto' => $v['men_instrucoes'] ?? null,
                        'cobrar_juros_multa' => $request->boolean('men_juros'),
                        'gerado_por' => auth()->id(),
                    ]);
                }
            }

            // Handoff (doc): o card do CRM vai automaticamente para GANHO
            if (!empty($v['oportunidade_id'])) {
                $op = Oportunidade::find($v['oportunidade_id']);
                if ($op && in_array($op->situacao, ['aberta', 'pausada'])) {
                    $op->update([
                        'situacao' => 'ganha',
                        'motivo_ganho_id' => $v['motivo_ganho_id'] ?? null,
                        'data_fechamento' => now(),
                    ]);
                    HistoricoOportunidade::registrar($op->id, 'movimentacao',
                        'GANHO: matrícula ' . $identificador . ' concluída pelo assistente. Card movido para a coluna GANHO.');
                }
            }

            return $matricula;
        });

        return redirect()->route('academico.matriculas.ficha', $matricula)
            ->with('success', 'Matrícula ' . $matricula->numero_matricula . ' concluída: aluno ativo na turma, disciplinas liberadas e financeiro lançado no Contas a Receber.');
    }
}
