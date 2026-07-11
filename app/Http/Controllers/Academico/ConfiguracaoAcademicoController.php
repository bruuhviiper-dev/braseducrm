<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracaoAcademico;
use App\Models\SolucaoPersonalizada;
use Illuminate\Http\Request;

/**
 * 167 Configuração do Acadêmico (doc revisão): painel de controle das regras de
 * negócio em 6 abas — Dados Básicos, Assinatura Contrato Eletrônico, Templates,
 * Automação de Mensagens, Matrícula (combo) e Soluções Personalizadas (SOLPER).
 * As chaves são guardadas no JSON `configuracoes` do singleton.
 */
class ConfiguracaoAcademicoController extends Controller
{
    /** Chaves booleanas (toggles) por aba. */
    private const TOGGLES = [
        // Dados Básicos
        'confirmar_apos_pagamento', 'gerar_mensalidades_ativas', 'bloquear_sem_vagas', 'bloquear_duplicada',
        'enviar_dados_acesso', 'enviar_whatsapp', 'cpf_obrigatorio', 'cpf_como_senha',
        'frequencia_portal', 'impedir_conflito_salas', 'carregar_como_falta',
        'desconsiderar_justificadas', 'concluir_confirmados',
        // Assinatura Contrato Eletrônico
        'usar_eduqsign', 'nao_enviar_email_contrato', 'whatsapp_cobranca_assinatura',
        'mensagem_funcao_23', 'validacao_email', 'somente_vinculado_turma',
        'assinar_com_operador', 'usar_clicksign',
        // Automação de Mensagens
        'aniv_alunos', 'aniv_profissionais',
        // Matrícula (combo)
        'combo_permitir', 'combo_crm',
    ];

    private const TEXTOS = [
        'tipo_requerimento_id', 'motivo_cancelamento_padrao', 'identificador_matricula', 'formato_pdf',
        'ponto_inicio_contratos', 'modelo_contrato_id', 'situacao_matricula_gatilho', 'mensagem_aluno_email',
        'msg_presencial_html', 'msg_presencial_texto', 'msg_ead_html', 'msg_ead_texto',
        'aniv_alunos_template', 'aniv_profissionais_template',
        'combo_forma_pgto_matricula', 'combo_plano_conta_matricula_id',
        'combo_forma_pgto_mensalidade', 'combo_plano_conta_mensalidade_id',
        'combo_limitador_parcelas', 'combo_limitador_desconto',
    ];

    public function index()
    {
        $config = ConfiguracaoAcademico::current();

        return view('academico.configuracao.index', [
            'config' => $config,
            'cfg' => (array) ($config->configuracoes ?? []),
            'solpers' => SolucaoPersonalizada::orderBy('chave')->get(),
            'tiposRequerimento' => \App\Models\TipoRequerimento::orderBy('nome')->get(),
            'motivosCancelamento' => \App\Models\MotivoCancelamentoMatricula::orderBy('nome')->get(),
            'modelosDocumento' => \App\Models\ModeloDocumento::orderBy('nome')->get(),
            'planosConta' => \App\Models\PlanoContas::where('ativo', true)->orderBy('codigo')->get(),
            'operadores' => \App\Models\User::where('ativo', true)->orderBy('nome')->get(),
        ]);
    }

    public function update(Request $request)
    {
        $config = ConfiguracaoAcademico::current();
        $cfg = (array) ($config->configuracoes ?? []);

        foreach (self::TOGGLES as $chave) {
            $cfg[$chave] = $request->boolean($chave);
        }
        foreach (self::TEXTOS as $chave) {
            if ($request->has($chave)) {
                $cfg[$chave] = $request->input($chave) ?: null;
            }
        }
        // Assinantes digitais extras (testemunhas fixas)
        $cfg['assinantes_extras'] = array_map('intval', (array) $request->input('assinantes_extras', []));
        // Apelidos personalizados (Nome Oficial → Nome Curto)
        $apelidos = [];
        foreach ((array) $request->input('apelidos', []) as $a) {
            if (!empty($a['oficial']) && !empty($a['apelido'])) {
                $apelidos[] = ['oficial' => $a['oficial'], 'apelido' => $a['apelido']];
            }
        }
        $cfg['apelidos'] = $apelidos;

        $config->update([
            'assinatura_eletronica' => $request->boolean('usar_eduqsign'),
            'envio_email_matricula' => $request->boolean('enviar_dados_acesso'),
            'aniversariante_automatico' => $request->boolean('aniversariantes_ativo'),
            'configuracoes' => $cfg,
        ]);

        // Aba Soluções Personalizadas: grid chave/valor
        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            SolucaoPersonalizada::query()->delete();
            foreach ((array) $request->input('solpers', []) as $s) {
                if (!empty($s['chave']) && isset($s['valor']) && $s['valor'] !== '') {
                    SolucaoPersonalizada::updateOrCreate(['chave' => trim($s['chave'])], ['valor' => trim($s['valor'])]);
                }
            }
        });

        return redirect()->route('academico.configuracao.index')->with('success', 'Configuração do Acadêmico salva. As regras valem imediatamente para as novas ações (não retroagem).');
    }
}
