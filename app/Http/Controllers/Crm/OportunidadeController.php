<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\EtapaFunil;
use App\Models\Funil;
use App\Models\Indicacao;
use App\Models\Interessado;
use App\Models\Oportunidade;
use App\Models\OrigemInteressado;
use App\Models\TagCrm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OportunidadeController extends Controller
{
    public function index()
    {
        $oportunidades = Oportunidade::with(['interessado', 'funil', 'etapaFunil', 'consultor', 'curso'])
            ->orderBy('id', 'desc')->paginate(15);
        $motivosPerda = \App\Models\MotivoPerda::orderBy('nome')->get();

        return view('crm.oportunidades.index', compact('oportunidades', 'motivosPerda'));
    }

    /** EDUQ: "Ganho" significa efetivação de matrícula. */
    public function ganhar(Request $request, Oportunidade $oportunidade)
    {
        $v = $request->validate(['motivo_ganho_id' => 'nullable|exists:motivos_ganho,id']);
        $oportunidade->update([
            'situacao' => 'ganha',
            'motivo_ganho_id' => $v['motivo_ganho_id'] ?? null,
            'data_fechamento' => now(),
        ]);

        \App\Models\HistoricoOportunidade::registrar($oportunidade->id, 'movimentacao', 'Oportunidade dada como GANHO.');
        $msgAcoes = $this->executarAcoesAutomaticas($oportunidade);

        return back()->with('success', 'Oportunidade marcada como Ganha (efetivação de matrícula).' . $msgAcoes);
    }

    /**
     * Ações automáticas do EDUQ (256): no gatilho "Oportunidade Ganha", duplica o card
     * para o funil de Pós-Vendas trocando o responsável (ex.: secretaria acompanha o
     * acolhimento do aluno). O card duplicado não contabiliza valor (protege o DRE comercial).
     */
    private function executarAcoesAutomaticas(Oportunidade $oportunidade): string
    {
        $acoes = \App\Models\AcaoAutomaticaCrm::where('ativo', true)
            ->where('gatilho', 'oportunidade_ganha')
            ->where('acao', 'duplicar_oportunidade')
            ->whereNotNull('funil_destino_id')
            ->get();

        $executadas = 0;
        foreach ($acoes as $acao) {
            $primeiraEtapa = EtapaFunil::where('funil_id', $acao->funil_destino_id)->orderBy('ordem')->first();
            if (!$primeiraEtapa) {
                continue; // funil de destino sem etapas configuradas
            }
            Oportunidade::create([
                'interessado_id' => $oportunidade->interessado_id,
                'origem_id' => $oportunidade->origem_id,
                'funil_id' => $acao->funil_destino_id,
                'etapa_funil_id' => $primeiraEtapa?->id,
                'consultor_id' => $acao->responsavel_destino_id ?: $oportunidade->consultor_id,
                'curso_id' => $oportunidade->curso_id,
                'titulo' => 'Pós-venda: ' . ($oportunidade->titulo ?: ($oportunidade->interessado?->nome ?? 'aluno')),
                'valor' => null, // não soma na faturação do funil comercial
                'situacao' => 'aberta',
                'observacoes' => 'Gerada automaticamente pela ação "' . $acao->nome . '" ao ganhar a oportunidade #' . $oportunidade->id . '.',
            ]);
            $executadas++;
        }

        return $executadas ? " Card duplicado para o funil de pós-vendas ({$executadas} ação automática executada)." : '';
    }

    /** EDUQ: a justificativa é obrigatória ao dar o card como perdido. */
    public function perder(Request $request, Oportunidade $oportunidade)
    {
        $v = $request->validate([
            'motivo_perda_id' => 'required|exists:motivos_perda,id',
        ], [
            'motivo_perda_id.required' => 'O motivo da perda é obrigatório (alimenta o gráfico de motivos de perda do painel comercial).',
        ]);

        $oportunidade->update([
            'situacao' => 'perdida',
            'motivo_perda_id' => $v['motivo_perda_id'],
            'data_fechamento' => now(),
        ]);
        \App\Models\HistoricoOportunidade::registrar($oportunidade->id, 'movimentacao',
            'Oportunidade dada como PERDA (motivo: ' . ($oportunidade->fresh()->motivoPerda->nome ?? '-') . ').');

        return back()->with('success', 'Oportunidade marcada como Perdida.');
    }

    public function create()
    {
        return view('crm.oportunidades.form', $this->dados(null));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);

        // Roleta do CRM (docs do EDUQ): lead sem responsável é distribuído automaticamente
        // pela proporção configurada (A recebe 3, B recebe 2...), priorizando o topo da lista
        $viaRoleta = false;
        if (empty($data['oportunidade']['consultor_id']) && \App\Models\ConfiguracaoCrm::current()->roleta_ativa) {
            $data['oportunidade']['consultor_id'] = ConfiguracaoCrmController::proximoOperadorRoleta();
            $viaRoleta = (bool) $data['oportunidade']['consultor_id'];
        }

        DB::transaction(function () use ($data) {
            $op = Oportunidade::create($data['oportunidade']);
            $op->tags()->sync($data['tags']);
        });

        return redirect()->route('crm.oportunidades.index')
            ->with('success', 'Oportunidade criada com sucesso.' . ($viaRoleta ? ' Lead distribuído pela roleta do CRM.' : ''));
    }

    /** Ficha do card (doc CRM): 6 abas à esquerda + linha do tempo à direita. */
    public function edit(Oportunidade $oportunidade)
    {
        $oportunidade->load(['tags', 'interessado', 'consultor', 'origem', 'indicacao', 'etapaFunil', 'funil',
            'historicos.user', 'atividades.evento', 'atividades.responsavel', 'interesses', 'linksMatricula.abertura']);

        $propostas = \App\Models\PropostaCrm::where('oportunidade_id', $oportunidade->id)->orderByDesc('id')->get();
        $matriculasInteressado = collect();
        if ($oportunidade->interessado) {
            $matriculasInteressado = \App\Models\Matricula::with('turmaMontada')
                ->whereHas('aluno.pessoa', fn ($q) => $q->where('nome', $oportunidade->interessado->nome)
                    ->orWhere('email', $oportunidade->interessado->email))->get();
        }

        return view('crm.oportunidades.ficha', $this->dados($oportunidade) + [
            'propostas' => $propostas,
            'matriculasInteressado' => $matriculasInteressado,
            'eventos' => \App\Models\EventoCrm::orderBy('nome')->get(),
            'aberturas' => \App\Models\AberturaMatriculaOnline::where('ativo', true)->orderBy('nome')->get(),
            'motivosGanho' => \App\Models\MotivoGanho::orderBy('nome')->get(),
            'motivosPerda' => \App\Models\MotivoPerda::orderBy('nome')->get(),
        ]);
    }

    public function update(Request $request, Oportunidade $oportunidade)
    {
        $data = $this->validar($request);
        $antes = $oportunidade->only(['etapa_funil_id', 'consultor_id']);
        DB::transaction(function () use ($oportunidade, $data, $antes) {
            $oportunidade->update($data['oportunidade']);
            $oportunidade->tags()->sync($data['tags']);
            // Histórico da movimentação (doc CRM): auditoria de mudanças de etapa e responsável
            if ($antes['etapa_funil_id'] != $oportunidade->etapa_funil_id) {
                \App\Models\HistoricoOportunidade::registrar($oportunidade->id, 'movimentacao',
                    'Etapa alterada para "' . ($oportunidade->etapaFunil->nome ?? '-') . '".');
            }
            if ($antes['consultor_id'] != $oportunidade->consultor_id) {
                \App\Models\HistoricoOportunidade::registrar($oportunidade->id, 'movimentacao',
                    'Responsável alterado para ' . ($oportunidade->consultor->nome ?? '-') . '.');
            }
        });

        return back()->with('success', 'Oportunidade atualizada com sucesso.');
    }

    /** Linha do tempo: anotação (com anexo opcional) na base do painel de histórico. */
    public function anotar(Request $request, Oportunidade $oportunidade)
    {
        $v = $request->validate([
            'texto' => 'nullable|string|max:4000',
            'arquivo' => 'nullable|file|max:10240',
        ]);
        if (empty($v['texto']) && !$request->hasFile('arquivo')) {
            return back()->with('error', 'Escreva uma anotação ou anexe um arquivo.');
        }
        $caminho = $request->hasFile('arquivo') ? $request->file('arquivo')->store('oportunidades', 'public') : null;
        \App\Models\HistoricoOportunidade::registrar($oportunidade->id, $caminho ? 'anexo' : 'anotacao', $v['texto'] ?? null, $caminho);

        return back()->with('success', 'Registro adicionado à linha do tempo.');
    }

    /** Agendar atividade (ícone de calendário do card/painel — alimenta a aba Atividades e as notificações). */
    public function agendarAtividade(Request $request, Oportunidade $oportunidade)
    {
        $v = $request->validate([
            'evento_crm_id' => 'nullable|exists:eventos_crm,id',
            'titulo' => 'required|string|max:255',
            'data_agendamento' => 'required|date',
            'descricao' => 'nullable|string|max:2000',
        ]);
        \App\Models\AtividadeOportunidade::create($v + [
            'oportunidade_id' => $oportunidade->id,
            'responsavel_id' => $oportunidade->consultor_id ?: auth()->id(),
            'situacao' => 'pendente',
        ]);
        \App\Models\HistoricoOportunidade::registrar($oportunidade->id, 'atividade',
            'Atividade agendada: ' . $v['titulo'] . ' para ' . \Carbon\Carbon::parse($v['data_agendamento'])->format('d/m/Y H:i') . '.');

        return back()->with('success', 'Atividade agendada.');
    }

    public function concluirAtividade(Oportunidade $oportunidade, \App\Models\AtividadeOportunidade $atividade)
    {
        abort_unless($atividade->oportunidade_id === $oportunidade->id, 404);
        $atividade->update(['situacao' => 'concluida', 'data_conclusao' => now()]);
        \App\Models\HistoricoOportunidade::registrar($oportunidade->id, 'atividade', 'Atividade concluída: ' . $atividade->titulo . '.');

        return back()->with('success', 'Atividade concluída.');
    }

    /** Estrelas de qualificação (1-5) direto no card do Kanban ou na ficha. */
    public function estrelas(Request $request, Oportunidade $oportunidade)
    {
        $v = $request->validate(['estrelas' => 'required|integer|min:0|max:5']);
        $oportunidade->update(['estrelas' => $v['estrelas']]);

        return response()->json(['success' => true]);
    }

    /** Aba Interesses (doc CRM): múltiplos cursos na mesma ficha do lead. */
    public function interesseAdicionar(Request $request, Oportunidade $oportunidade)
    {
        $v = $request->validate(['curso_id' => 'required|exists:cursos,id']);
        $oportunidade->interesses()->syncWithoutDetaching([$v['curso_id']]);

        return back()->with('success', 'Curso de interesse adicionado.');
    }

    public function interesseRemover(Oportunidade $oportunidade, Curso $curso)
    {
        $oportunidade->interesses()->detach($curso->id);

        return back()->with('success', 'Curso de interesse removido.');
    }

    /**
     * Gerar link de matrícula online (doc CRM): autoatendimento. Com data de expiração,
     * o sistema agenda automaticamente uma atividade de cobrança no vencimento do link.
     */
    public function gerarLink(Request $request, Oportunidade $oportunidade)
    {
        $v = $request->validate([
            'abertura_matricula_id' => 'required|exists:aberturas_matricula_online,id',
            'novo_checkout' => 'nullable|boolean',
            'expira_em' => 'nullable|date|after:now',
        ]);
        $link = \App\Models\LinkMatriculaOnline::create([
            'oportunidade_id' => $oportunidade->id,
            'abertura_matricula_id' => $v['abertura_matricula_id'],
            'novo_checkout' => (bool) ($v['novo_checkout'] ?? true),
            'expira_em' => $v['expira_em'] ?? null,
            'token' => \Illuminate\Support\Str::random(40),
        ]);
        if ($link->expira_em) {
            \App\Models\AtividadeOportunidade::create([
                'oportunidade_id' => $oportunidade->id,
                'responsavel_id' => $oportunidade->consultor_id ?: auth()->id(),
                'titulo' => 'Cobrar interessado: link de matrícula expira',
                'descricao' => 'O link de matrícula online enviado expira em ' . $link->expira_em->format('d/m/Y H:i') . '. Se o pagamento não foi concluído, retomar contato.',
                'data_agendamento' => $link->expira_em,
                'situacao' => 'pendente',
            ]);
        }
        \App\Models\HistoricoOportunidade::registrar($oportunidade->id, 'disparo',
            'Link de matrícula online gerado' . ($link->expira_em ? ' (expira em ' . $link->expira_em->format('d/m/Y H:i') . ')' : '') . '.');

        return back()->with('success', 'Link gerado.')->with('link_gerado', route('matricula-link', $link->token));
    }

    public function destroy(Oportunidade $oportunidade)
    {
        $oportunidade->delete();

        return redirect()->route('crm.oportunidades.index')->with('success', 'Oportunidade removida com sucesso.');
    }

    public function moverEtapa(Request $request, Oportunidade $oportunidade)
    {
        $validated = $request->validate(['etapa_funil_id' => 'required|exists:etapas_funil,id']);
        $anterior = $oportunidade->etapaFunil?->nome;
        $oportunidade->update(['etapa_funil_id' => $validated['etapa_funil_id']]);
        \App\Models\HistoricoOportunidade::registrar($oportunidade->id, 'movimentacao',
            'Card movido da etapa "' . ($anterior ?? '-') . '" para "' . ($oportunidade->fresh()->etapaFunil->nome ?? '-') . '".');

        return response()->json(['success' => true]);
    }

    private function validar(Request $request): array
    {
        $v = $request->validate([
            'interessado_id' => 'required|exists:interessados,id',
            'origem_id' => 'nullable|exists:origens_interessado,id',
            'indicacao_id' => 'nullable|exists:indicacoes,id',
            'funil_id' => 'required|exists:funis,id',
            'etapa_funil_id' => 'required|exists:etapas_funil,id',
            'consultor_id' => 'nullable|exists:users,id',           // Responsável
            'curso_id' => 'nullable|exists:cursos,id',
            'titulo' => 'nullable|string|max:255',
            'valor' => 'nullable|numeric|min:0',
            'situacao' => 'nullable|in:aberta,ganha,perdida,pausada',
            'qualificacao' => 'nullable|in:quente,morno,frio',
            'estrelas' => 'nullable|integer|min:0|max:5',
            'midia' => 'nullable|string|max:255',
            'data_previsao_fechamento' => 'nullable|date',
            'motivacao_interesse' => 'nullable|string',
            'observacoes' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags_crm,id',
            // EDUQ: a justificativa é obrigatória ao dar um card como perdido (alimenta o gráfico de motivos de perda)
            'motivo_perda_id' => 'required_if:situacao,perdida|nullable|exists:motivos_perda,id',
            'motivo_ganho_id' => 'nullable|exists:motivos_ganho,id',
        ], [
            'motivo_perda_id.required_if' => 'Ao marcar a oportunidade como Perdida, o motivo da perda é obrigatório (alimenta o painel comercial).',
        ]);

        return [
            'oportunidade' => [
                'interessado_id' => $v['interessado_id'],
                'origem_id' => $v['origem_id'] ?? null,
                'indicacao_id' => $v['indicacao_id'] ?? null,
                'funil_id' => $v['funil_id'],
                'etapa_funil_id' => $v['etapa_funil_id'],
                'consultor_id' => $v['consultor_id'] ?? null,
                'curso_id' => $v['curso_id'] ?? null,
                'titulo' => $v['titulo'] ?? null,
                'valor' => $v['valor'] ?? null,
                'situacao' => $v['situacao'] ?? 'aberta',
                'motivo_perda_id' => ($v['situacao'] ?? null) === 'perdida' ? ($v['motivo_perda_id'] ?? null) : null,
                'motivo_ganho_id' => ($v['situacao'] ?? null) === 'ganha' ? ($v['motivo_ganho_id'] ?? null) : null,
                'data_fechamento' => in_array($v['situacao'] ?? '', ['ganha', 'perdida']) ? now() : null,
                'qualificacao' => $v['qualificacao'] ?? null,
                'estrelas' => (int) ($v['estrelas'] ?? 0),
                'midia' => $v['midia'] ?? null,
                'data_previsao_fechamento' => $v['data_previsao_fechamento'] ?? null,
                'motivacao_interesse' => $v['motivacao_interesse'] ?? null,
                'observacoes' => $v['observacoes'] ?? null,
            ],
            'tags' => array_map('intval', $v['tags'] ?? []),
        ];
    }

    private function dados(?Oportunidade $oportunidade): array
    {
        return [
            'oportunidade' => $oportunidade,
            'interessados' => Interessado::where('ativo', true)->orderBy('nome')->get(),
            'origens' => OrigemInteressado::orderBy('nome')->get(),
            'indicacoes' => Indicacao::orderByDesc('id')->get(),
            'funis' => Funil::where('ativo', true)->orderBy('nome')->get(),
            'etapas' => EtapaFunil::orderBy('ordem')->get(),
            'consultores' => User::where('ativo', true)->orderBy('nome')->get(),
            'cursos' => Curso::where('ativo', true)->orderBy('nome')->get(),
            'tagsList' => TagCrm::orderBy('nome')->get(),
        ];
    }
}
