<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\AssinaturaEletronica;
use App\Models\Atendimento;
use App\Models\Documento;
use App\Models\Disciplina;
use App\Models\EnadeRegistro;
use App\Models\Enturmacao;
use App\Models\Frequencia;
use App\Models\Horario;
use App\Models\Matricula;
use App\Models\MatrizCurricular;
use App\Models\ModeloDocumento;
use App\Models\MovimentacaoMatricula;
use App\Models\Nota;
use App\Models\Oportunidade;
use App\Models\Requerimento;
use App\Models\TituloReceber;
use App\Models\TurmaMontada;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Matrícula e Histórico (23 do EDUQ): a ficha completa da matrícula em abas —
 * Dados Básicos, Enturmações, Notas e Faltas, Calendário, Financeiro, Contratos e
 * Declarações, Documentos, Requerimentos, Atendimentos, Assinatura Eletrônica,
 * Horas Complementares, Enade, Histórico de Movimentações e Informações de Saúde.
 */
class FichaMatriculaController extends Controller
{
    public function ficha(Matricula $matricula)
    {
        $matricula->load([
            'aluno.pessoa', 'turma.curso', 'turma.matrizCurricular', 'turmaMontada',
            'formaIngresso', 'consultor', 'responsavelFinanceiro', 'matrizCurricular',
            'enturmacoes.disciplina', 'enturmacoes.turmaMontada',
            'movimentacoes.user', 'enades', 'assinaturasEletronicas',
            'entregasDocumento.documento', 'horasComplementares',
        ]);
        $pessoa = $matricula->aluno?->pessoa;

        // ---- Cards de status (topo da ficha) ----
        $titulos = TituloReceber::where(function ($q) use ($matricula, $pessoa) {
            $q->where('matricula_id', $matricula->id);
            if ($pessoa) {
                $q->orWhere('pessoa_id', $pessoa->id);
            }
        })->orderBy('data_vencimento')->get();

        $vencidos = $titulos->filter(fn ($t) => in_array($t->situacao, ['aberto', 'vencido']) && $t->data_vencimento?->isPast());
        $assinaturas = $matricula->assinaturasEletronicas;
        $docsObrigatorios = Documento::where('ativo', true)->where('obrigatorio', true)
            ->where(fn ($q) => $q->whereNull('curso_id')->orWhere('curso_id', $matricula->turma?->curso_id))->get();
        $entregues = $matricula->entregasDocumento->where('entregue', true);
        $pendentesAprovacao = $entregues->where('aprovado', null)->whereNotNull('arquivo');
        $docsPendentes = $docsObrigatorios->whereNotIn('id', $entregues->pluck('documento_id'));

        $status = [
            'matricula' => ucfirst(str_replace('_', ' ', $matricula->situacao)),
            'contrato' => $assinaturas->isEmpty() ? 'Pendente' : ($assinaturas->every(fn ($a) => $a->situacao === 'assinado') ? 'Assinado' : 'Enviado'),
            'financeiro' => $vencidos->isNotEmpty() ? 'Inadimplente' : 'Em dia',
            'restricao' => $pessoa?->blacklist ? 'Com restrição' : 'Sem restrição',
            'documentos' => $docsPendentes->isNotEmpty() ? 'Pendente' : 'Completo',
        ];

        // "Ver oportunidade que gerou essa matrícula"
        $oportunidadeOrigem = $pessoa ? Oportunidade::whereHas('interessado', function ($q) use ($pessoa) {
            $q->where('nome', $pessoa->nome);
            if ($pessoa->email) {
                $q->orWhere('email', $pessoa->email);
            }
        })->where('situacao', 'ganha')->orderByDesc('id')->first() : null;

        // ---- Notas e Faltas por disciplina enturmada ----
        $disciplinasNotas = [];
        $idsDisciplinas = $matricula->enturmacoes->pluck('disciplina_id')
            ->merge(Horario::where('turma_montada_id', $matricula->turma_montada_id)->pluck('disciplina_id'))
            ->unique()->values();
        foreach (Disciplina::whereIn('id', $idsDisciplinas)->get() as $disc) {
            $notas = Nota::with('item')->where('matricula_id', $matricula->id)
                ->where('disciplina_id', $disc->id)->whereNotNull('nota')->get();
            $somaP = $somaPeso = 0;
            foreach ($notas as $n) {
                $peso = $n->item?->peso ?? 1;
                $somaP += $n->nota * $peso;
                $somaPeso += $peso;
            }
            $media = $somaPeso > 0 ? round($somaP / $somaPeso, 2) : null;
            $totalAulas = Frequencia::where('matricula_id', $matricula->id)->where('disciplina_id', $disc->id)->count();
            $presencas = Frequencia::where('matricula_id', $matricula->id)->where('disciplina_id', $disc->id)
                ->whereIn('status', ['presente', 'justificada'])->count();
            $faltas = $totalAulas - $presencas;
            $freq = $totalAulas > 0 ? round(($presencas / $totalAulas) * 100, 2) : null;

            $resultado = 'Cursando';
            if ($media !== null) {
                if ($freq !== null && $freq < 75) {
                    $resultado = 'Reprovado por Falta';
                } elseif ($media < 7) {
                    $resultado = 'Reprovado por Nota';
                } else {
                    $resultado = 'Aprovado';
                }
            }
            $disciplinasNotas[] = ['disciplina' => $disc, 'media' => $media, 'faltas' => $faltas ?: null, 'frequencia' => $freq, 'resultado' => $resultado];
        }

        // ---- Calendário (grade semanal da turma montada) ----
        $horarios = Horario::with(['disciplina', 'sala'])
            ->where('turma_montada_id', $matricula->turma_montada_id)
            ->orderBy('dia_semana')->orderBy('hora_inicio')->get();

        // ---- Financeiro ----
        $fin = [
            'pago' => $titulos->where('situacao', 'pago')->sum('valor_pago') ?: $titulos->where('situacao', 'pago')->sum('valor_original'),
            'aberto' => $titulos->whereIn('situacao', ['aberto', 'vencido'])->sum('valor_original'),
            'vencido' => $vencidos->sum('valor_original'),
            'juros' => $vencidos->sum('valor_juros'),
            'multa' => $vencidos->sum('valor_multa'),
        ];

        return view('academico.matriculas.ficha', [
            'matricula' => $matricula,
            'pessoa' => $pessoa,
            'status' => $status,
            'oportunidadeOrigem' => $oportunidadeOrigem,
            'disciplinasNotas' => $disciplinasNotas,
            'horarios' => $horarios,
            'titulos' => $titulos,
            'fin' => $fin,
            'entregues' => $entregues,
            'pendentesAprovacao' => $pendentesAprovacao,
            'docsPendentes' => $docsPendentes,
            'requerimentos' => Requerimento::with('tipoRequerimento')->where('matricula_id', $matricula->id)->orderByDesc('id')->get(),
            'atendimentos' => $pessoa ? Atendimento::with('categoria')->where('pessoa_id', $pessoa->id)->orderByDesc('id')->get() : collect(),
            'modelosDocumento' => ModeloDocumento::where('ativo', true)->orderBy('nome')->get(),
            'turmasMontadas' => TurmaMontada::with('turma')->orderByDesc('id')->get(),
            'matrizes' => MatrizCurricular::orderBy('nome')->get(),
            'formasIngresso' => \App\Models\FormaIngresso::orderBy('nome')->get(),
            'operadores' => User::where('ativo', true)->orderBy('nome')->get(),
            'pessoas' => \App\Models\Pessoa::orderBy('nome')->get(),
            'disciplinas' => Disciplina::where('ativo', true)->orderBy('nome')->get(),
        ]);
    }

    public function salvar(Request $request, Matricula $matricula)
    {
        $v = $request->validate([
            'turma_montada_id' => 'nullable|exists:turmas_montadas,id',
            'matriz_curricular_id' => 'nullable|exists:matrizes_curriculares,id',
            'data_matricula' => 'nullable|date',
            'previsao_conclusao' => 'nullable|date',
            'data_inicio_aulas' => 'nullable|date',
            'forma_ingresso_id' => 'nullable|exists:formas_ingresso,id',
            'como_conheceu' => 'nullable|string|max:255',
            'consultor_id' => 'nullable|exists:users,id',
            'responsavel_financeiro_id' => 'nullable|exists:pessoas,id',
            'observacoes_saude' => 'nullable|string',
        ]);

        $matricula->update(collect($v)->except('observacoes_saude')->all()
            + ['exibir_historico_prioritario' => $request->boolean('exibir_historico_prioritario')]);

        if ($request->has('observacoes_saude') && $matricula->aluno?->pessoa) {
            $matricula->aluno->pessoa->update(['observacoes_saude' => $v['observacoes_saude'] ?? null]);
        }

        MovimentacaoMatricula::registrar($matricula->id, 'Ficha da matrícula atualizada', $matricula->situacao);

        return back()->with('success', 'Matrícula salva com sucesso.');
    }

    /** Enturmações: adicionar disciplina (normal, equivalente ou optativa) */
    public function enturmar(Request $request, Matricula $matricula)
    {
        $v = $request->validate([
            'disciplina_id' => 'required|exists:disciplinas,id',
            'turma_montada_id' => 'nullable|exists:turmas_montadas,id',
            'data_inicio' => 'nullable|date',
            'tipo' => 'nullable|in:normal,equivalente,optativa',
        ]);
        $v['matricula_id'] = $matricula->id;
        $v['turma_montada_id'] = $v['turma_montada_id'] ?? $matricula->turma_montada_id;
        $v['tipo'] = $v['tipo'] ?? 'normal';
        Enturmacao::create($v);
        MovimentacaoMatricula::registrar($matricula->id, 'Disciplina enturmada (' . ($v['tipo']) . ')', $matricula->situacao, 'enturmacao');

        return back()->with('success', 'Disciplina enturmada.');
    }

    public function desenturmar(Matricula $matricula, Enturmacao $enturmacao)
    {
        abort_unless($enturmacao->matricula_id === $matricula->id, 404);
        $enturmacao->delete();
        MovimentacaoMatricula::registrar($matricula->id, 'Enturmação removida', $matricula->situacao, 'enturmacao');

        return back()->with('success', 'Enturmação removida.');
    }

    /** Transferir de turma: muda a turma montada e devolve as enturmações para a nova turma */
    public function transferirTurma(Request $request, Matricula $matricula)
    {
        $v = $request->validate(['turma_montada_id' => 'required|exists:turmas_montadas,id']);
        $antiga = $matricula->turmaMontada?->sigla ?? $matricula->turmaMontada?->nome ?? '—';
        $matricula->update(['turma_montada_id' => $v['turma_montada_id']]);
        $matricula->enturmacoes()->update(['turma_montada_id' => $v['turma_montada_id']]);
        $nova = TurmaMontada::find($v['turma_montada_id']);
        MovimentacaoMatricula::registrar($matricula->id, "Transferido de turma: {$antiga} → " . ($nova->sigla ?? $nova->nome ?? $nova->id), $matricula->situacao, 'transferencia');

        return back()->with('success', 'Aluno transferido de turma.');
    }

    /** Documentos: aprovar as entregas pendentes de aprovação */
    public function aprovarDocumentos(Matricula $matricula)
    {
        $n = $matricula->entregasDocumento()->where('entregue', true)->whereNull('aprovado')->update(['aprovado' => true]);
        MovimentacaoMatricula::registrar($matricula->id, "Documentos aprovados ({$n})", $matricula->situacao, 'documentos');

        return back()->with('success', "{$n} documento(s) aprovado(s).");
    }

    /** Enade */
    public function enadeAdicionar(Request $request, Matricula $matricula)
    {
        $v = $request->validate([
            'edicao' => 'required|string|max:20',
            'situacao' => 'required|in:ingressante,concluinte,dispensado',
            'observacao' => 'nullable|string|max:255',
        ]);
        $matricula->enades()->create($v);

        return back()->with('success', 'Edição do Enade adicionada.');
    }

    public function enadeRemover(Matricula $matricula, EnadeRegistro $enade)
    {
        abort_unless($enade->matricula_id === $matricula->id, 404);
        $enade->delete();

        return back()->with('success', 'Edição do Enade removida.');
    }

    /** Assinatura Eletrônica: envia documento p/ assinatura (gera link) ou anexa já assinado */
    public function assinaturaCriar(Request $request, Matricula $matricula)
    {
        $v = $request->validate([
            'documento' => 'required|string|max:255',
            'ja_assinado' => 'nullable|boolean',
        ]);
        $matricula->assinaturasEletronicas()->create([
            'documento' => $v['documento'],
            'situacao' => $request->boolean('ja_assinado') ? 'assinado' : 'pendente',
            'token' => $request->boolean('ja_assinado') ? null : Str::random(32),
        ]);
        MovimentacaoMatricula::registrar($matricula->id, 'Documento enviado para assinatura eletrônica: ' . $v['documento'], $matricula->situacao, 'assinatura');

        return back()->with('success', $request->boolean('ja_assinado')
            ? 'Documento assinado registrado.'
            : 'Documento enviado: link de assinatura gerado (copie e envie ao aluno).');
    }

    public function assinaturaRemover(Matricula $matricula, AssinaturaEletronica $assinatura)
    {
        abort_unless($assinatura->matricula_id === $matricula->id, 404);
        $assinatura->delete();

        return back()->with('success', 'Documento removido da assinatura eletrônica.');
    }
}
