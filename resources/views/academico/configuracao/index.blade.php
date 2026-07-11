@extends('layouts.app')
@section('title', 'Configuração do Acadêmico')

@php
    $t = fn ($chave, $padrao = false) => (bool) ($cfg[$chave] ?? $padrao);
    $v = fn ($chave, $padrao = '') => $cfg[$chave] ?? $padrao;
    $apelidosJs = array_values((array) ($cfg['apelidos'] ?? []));
    $solpersJs = [];
    foreach ($solpers as $s) { $solpersJs[] = ['chave' => $s->chave, 'valor' => $s->valor]; }
    $dadosJs = ['apelidos' => $apelidosJs, 'solpers' => $solpersJs];
@endphp

@section('content')
<div x-data="confAcad(@js($dadosJs))" class="max-w-5xl mx-auto">

    <div class="mb-3">
        <h1 class="text-lg font-bold text-gray-800"><span class="text-gray-400 font-normal">167</span> Configuração do Acadêmico</h1>
        <p class="text-xs text-gray-400">Painel de controle das regras de negócio — as chaves valem imediatamente para as novas ações e não retroagem.</p>
    </div>

    @if(session('success'))<div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>@endif

    <form method="POST" action="{{ route('academico.configuracao.index') }}" class="bg-white rounded-xl border shadow-sm">
        @csrf @method('PUT')

        {{-- Abas --}}
        <div class="flex overflow-x-auto border-b text-sm">
            @foreach(['Dados Básicos', 'Assinatura Contrato Eletrônico', 'Templates', 'Automação de Mensagens', 'Matrícula (combo)', 'Soluções Personalizadas'] as $i => $tab)
            <button type="button" @click="aba = {{ $i }}" :class="aba === {{ $i }} ? 'border-blue-500 text-blue-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-2.5 border-b-2 whitespace-nowrap">{{ $tab }}</button>
            @endforeach
        </div>

        {{-- ============ ABA 1: Dados Básicos ============ --}}
        <div x-show="aba === 0" class="p-6 space-y-5">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-xs text-yellow-800">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i>Com a trava de pagamento ativa, o aluno só muda para <b>Confirmado</b> após a compensação da taxa de matrícula; as mensalidades futuras nascem inativas e só são validadas após esse primeiro pagamento.
            </div>

            <p class="text-xs font-bold text-gray-400 uppercase">Regras de Matrícula e Vínculo Financeiro</p>
            <x-toggle-cfg nome="confirmar_apos_pagamento" :ativo="$t('confirmar_apos_pagamento', true)" rotulo="Confirmar a matrícula somente após o pagamento da matrícula?" />
            <x-toggle-cfg nome="gerar_mensalidades_ativas" :ativo="$t('gerar_mensalidades_ativas')" rotulo="Gerar as mensalidades ativas após a matrícula?" />
            <div>
                <label class="block text-xs text-gray-500 mb-1">Tipo de Requerimento (enviado ao aluno quando paga a matrícula)</label>
                <select name="tipo_requerimento_id" class="w-full border rounded-lg px-3 py-2 text-sm"><option value="">Nenhum</option>@foreach($tiposRequerimento as $tr)<option value="{{ $tr->id }}" @selected($v('tipo_requerimento_id') == $tr->id)>{{ $tr->nome }}</option>@endforeach</select>
            </div>
            <x-toggle-cfg nome="bloquear_sem_vagas" :ativo="$t('bloquear_sem_vagas')" rotulo="Bloquear matrícula em turmas sem vagas (Matrícula e Transferência)?" dica="Cruza com a capacidade máxima da Sala (39); inativo permite overbooking" />
            <x-toggle-cfg nome="bloquear_duplicada" :ativo="$t('bloquear_duplicada', true)" rotulo="Bloquear matrícula duplicada no mesmo curso?" />

            <p class="text-xs font-bold text-gray-400 uppercase pt-2">Portais, Acessos e LGPD/Segurança</p>
            <x-toggle-cfg nome="enviar_dados_acesso" :ativo="$t('enviar_dados_acesso', (bool) $config->envio_email_matricula)" rotulo="Enviar mensagem com os dados de acesso do Portal após confirmar a matrícula?" />
            <x-toggle-cfg nome="enviar_whatsapp" :ativo="$t('enviar_whatsapp')" rotulo="Enviar pelo WhatsApp?" dica="Sub-regra do campo anterior; inativo envia apenas por e-mail" />
            <x-toggle-cfg nome="cpf_obrigatorio" :ativo="$t('cpf_obrigatorio', true)" rotulo="Obrigar o preenchimento do CPF no cadastro de pessoa física?" />
            <x-toggle-cfg nome="cpf_como_senha" :ativo="$t('cpf_como_senha')" rotulo="Utilizar o CPF como senha padrão para novos alunos?" dica="Inativo: a primeira senha é a Data de Nascimento" />

            <p class="text-xs font-bold text-gray-400 uppercase pt-2">Lançamento de Notas, Frequências e Diário de Classe</p>
            <x-toggle-cfg nome="frequencia_portal" :ativo="$t('frequencia_portal')" rotulo="Habilitar lançamento de frequência no portal do aluno?" />
            <x-toggle-cfg nome="impedir_conflito_salas" :ativo="$t('impedir_conflito_salas')" rotulo="Impedir conflito de horários nas salas de aulas no lançamento do cronograma?" />
            <x-toggle-cfg nome="carregar_como_falta" :ativo="$t('carregar_como_falta')" rotulo="Carregar os lançamentos como falta quando ainda não houver registro de presença?" />
            <x-toggle-cfg nome="desconsiderar_justificadas" :ativo="$t('desconsiderar_justificadas')" rotulo="Desconsiderar aulas com faltas justificadas no cálculo da frequência" />
            <x-toggle-cfg nome="concluir_confirmados" :ativo="$t('concluir_confirmados')" rotulo="Concluir todos os alunos 'Confirmados' ao finalizar a turma?" />

            <p class="text-xs font-bold text-gray-400 uppercase pt-2">Automatizações de Secretaria e Identificadores</p>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Motivo de cancelamento padrão</label>
                    <select name="motivo_cancelamento_padrao" class="w-full border rounded-lg px-3 py-2 text-sm"><option value="">Selecione...</option>@foreach($motivosCancelamento as $mc)<option value="{{ $mc->nome }}" @selected($v('motivo_cancelamento_padrao') === $mc->nome)>{{ $mc->nome }}</option>@endforeach</select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Formato padrão de documentos PDF/A</label>
                    <select name="formato_pdf" class="w-full border rounded-lg px-3 py-2 text-sm">@foreach(['PDF/A-3U', 'PDF/A-1B', 'PDF/A-2B', 'PDF'] as $fp)<option value="{{ $fp }}" @selected($v('formato_pdf', 'PDF/A-3U') === $fp)>{{ $fp }}</option>@endforeach</select>
                </div>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Identificador de Matrícula (gerador de RA)</label>
                <input type="text" name="identificador_matricula" value="{{ $v('identificador_matricula', '#Ano.#SiglaDaTurmaMontada.#ChaveUnica') }}" class="w-full border rounded-lg px-3 py-2 text-sm font-mono">
                <p class="text-[11px] text-gray-400 mt-0.5">Ex.: aluno de 2026 na turma MKT → 2026.MKT.94827 (a chave única evita duplicidade).</p>
            </div>
        </div>

        {{-- ============ ABA 2: Assinatura Contrato Eletrônico ============ --}}
        <div x-show="aba === 1" x-cloak class="p-6 space-y-5">
            <p class="text-xs font-bold text-gray-400 uppercase">Plataforma de Assinatura e Notificações</p>
            <x-toggle-cfg nome="usar_eduqsign" :ativo="$t('usar_eduqsign', (bool) $config->assinatura_eletronica)" rotulo="Utilizar assinatura eletrônica nativa (OneSign) para assinar os contratos de matrícula?" />
            <x-toggle-cfg nome="nao_enviar_email_contrato" :ativo="$t('nao_enviar_email_contrato')" rotulo="Não enviar e-mail ao gerar o contrato eletrônico" dica="Inativo = envia e-mail automático com o link de assinatura" />
            <x-toggle-cfg nome="whatsapp_cobranca_assinatura" :ativo="$t('whatsapp_cobranca_assinatura')" rotulo="Enviar mensagem via WhatsApp de cobrança da assinatura do contrato pendente?" />
            <x-toggle-cfg nome="mensagem_funcao_23" :ativo="$t('mensagem_funcao_23')" rotulo="Enviar mensagem para contratos gerados na função 23 - Matrícula e Histórico?" />
            <x-toggle-cfg nome="validacao_email" :ativo="$t('validacao_email')" rotulo="Utilizar validação por e-mail (token) para assinar os contratos de matrícula?" />

            <p class="text-xs font-bold text-gray-400 uppercase pt-2">Gatilhos de Automação</p>
            <x-toggle-cfg nome="somente_vinculado_turma" :ativo="$t('somente_vinculado_turma')" rotulo="Enviar contrato automaticamente somente quando vinculado com turma ou curso" />
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Ponto de início para gerar os contratos automaticamente <span class="text-red-500">*</span></label>
                    <input type="date" name="ponto_inicio_contratos" value="{{ $v('ponto_inicio_contratos') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <p class="text-[11px] text-gray-400 mt-0.5">Matrículas anteriores a esta data são ignoradas pela automação.</p>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Situação da matrícula para gerar o contrato automaticamente</label>
                    <select name="situacao_matricula_gatilho" class="w-full border rounded-lg px-3 py-2 text-sm"><option value="">Selecione...</option>@foreach(['nao_confirmada' => 'Não confirmada', 'confirmada' => 'Confirmada', 'ativa' => 'Ativa'] as $sk => $sv)<option value="{{ $sk }}" @selected($v('situacao_matricula_gatilho') === $sk)>{{ $sv }}</option>@endforeach</select>
                </div>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Modelo de Contrato <span class="text-red-500">*</span></label>
                <select name="modelo_contrato_id" class="w-full border rounded-lg px-3 py-2 text-sm"><option value="">Selecione...</option>@foreach($modelosDocumento as $md)<option value="{{ $md->id }}" @selected($v('modelo_contrato_id') == $md->id)>{{ $md->nome }}</option>@endforeach</select>
            </div>

            <p class="text-xs font-bold text-gray-400 uppercase pt-2">Customização e Assinantes Extras</p>
            <div x-data="{ n: {{ mb_strlen($v('mensagem_aluno_email') ?? '') }} }">
                <label class="block text-xs text-gray-500 mb-1">Mensagem para o aluno (E-mail)</label>
                <textarea name="mensagem_aluno_email" rows="3" maxlength="5000" @input="n = $event.target.value.length" class="w-full border rounded-lg px-3 py-2 text-sm">{{ $v('mensagem_aluno_email') }}</textarea>
                <p class="text-right text-[11px] text-gray-400"><span x-text="n"></span> / 5000</p>
            </div>
            <x-toggle-cfg nome="assinar_com_operador" :ativo="$t('assinar_com_operador')" rotulo="Assinar digitalmente com o operador vinculado ao aluno se este possuir certificado digital?" />
            <div>
                <label class="block text-xs text-gray-500 mb-1">Assinantes digitais extras (testemunhas fixas)</label>
                <select name="assinantes_extras[]" multiple size="4" class="w-full border rounded-lg px-3 py-2 text-sm">@foreach($operadores as $op)<option value="{{ $op->id }}" @selected(in_array($op->id, (array) $v('assinantes_extras', [])))>{{ $op->nome }}</option>@endforeach</select>
            </div>
            <x-toggle-cfg nome="usar_clicksign" :ativo="$t('usar_clicksign')" rotulo="Utilizar ClickSign para assinar os contratos de matrícula?" dica="Manter desligado quando a assinatura nativa estiver ativa (evita conflito)" />
        </div>

        {{-- ============ ABA 3: Templates ============ --}}
        <div x-show="aba === 2" x-cloak class="p-6 space-y-5">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-xs text-yellow-800">
                Variáveis disponíveis: <b>[ALUNO]</b> nome do aluno · <b>[INSTITUICAO]</b> nome da escola · <b>[TURMA]</b> turma da matrícula. Não é preciso incluir as tags &lt;head&gt; e &lt;body&gt;.
            </div>
            <div x-data="{ sub: 0 }">
                <div class="flex border-b text-sm mb-3">
                    <button type="button" @click="sub = 0" :class="sub === 0 ? 'border-blue-500 text-blue-600 font-semibold' : 'border-transparent text-gray-500'" class="px-4 py-2 border-b-2">Matrículas Presenciais (23)</button>
                    <button type="button" @click="sub = 1" :class="sub === 1 ? 'border-blue-500 text-blue-600 font-semibold' : 'border-transparent text-gray-500'" class="px-4 py-2 border-b-2">Matrículas EAD (156)</button>
                </div>
                <div x-show="sub === 0" class="space-y-3">
                    <div><label class="block text-xs text-gray-500 mb-1">Mensagem (HTML)</label><textarea name="msg_presencial_html" rows="6" class="w-full border rounded-lg px-3 py-2 text-xs font-mono">{{ $v('msg_presencial_html', $config->email_matricula_template) }}</textarea></div>
                    <div><label class="block text-xs text-gray-500 mb-1">Mensagem (somente texto) — versão anti-SPAM</label><textarea name="msg_presencial_texto" rows="4" class="w-full border rounded-lg px-3 py-2 text-sm">{{ $v('msg_presencial_texto') }}</textarea></div>
                </div>
                <div x-show="sub === 1" x-cloak class="space-y-3">
                    <div><label class="block text-xs text-gray-500 mb-1">Mensagem (HTML)</label><textarea name="msg_ead_html" rows="6" class="w-full border rounded-lg px-3 py-2 text-xs font-mono">{{ $v('msg_ead_html') }}</textarea></div>
                    <div><label class="block text-xs text-gray-500 mb-1">Mensagem (somente texto)</label><textarea name="msg_ead_texto" rows="4" class="w-full border rounded-lg px-3 py-2 text-sm">{{ $v('msg_ead_texto') }}</textarea></div>
                </div>
            </div>

            <div>
                <p class="text-xs font-bold text-gray-400 uppercase mb-2">Apelidos Personalizados <span class="normal-case font-normal">(o portal exibe o apelido no lugar do nome oficial)</span></p>
                <table class="w-full text-sm mb-2">
                    <thead><tr class="text-left text-[11px] text-gray-400 border-b"><th class="py-1">NOME OFICIAL</th><th>NOME CURTO/ABREVIAÇÃO</th><th class="w-10"></th></tr></thead>
                    <tbody>
                        <template x-for="(a, i) in apelidos" :key="i">
                            <tr class="border-b border-gray-50">
                                <td class="py-1 pr-2"><input type="text" :name="'apelidos[' + i + '][oficial]'" x-model="a.oficial" class="w-full border rounded-lg px-2 py-1.5 text-sm" placeholder="Ex.: Progresso Virtual"></td>
                                <td class="pr-2"><input type="text" :name="'apelidos[' + i + '][apelido]'" x-model="a.apelido" class="w-full border rounded-lg px-2 py-1.5 text-sm" placeholder="Ex.: Progresso EAD"></td>
                                <td><button type="button" @click="apelidos.splice(i, 1)" class="text-red-400 hover:text-red-600"><i class="fa-regular fa-trash-can"></i></button></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <button type="button" @click="apelidos.push({ oficial: '', apelido: '' })" class="text-xs px-3 py-1.5 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50"><i class="fa-solid fa-plus mr-1"></i>Apelido</button>
            </div>
        </div>

        {{-- ============ ABA 4: Automação de Mensagens ============ --}}
        <div x-show="aba === 3" x-cloak class="p-6 space-y-5">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-xs text-yellow-800">
                As mensagens são agendadas para a janela da manhã (<b>08:00 às 10:00</b>) e a verificação roda <b>uma única vez por dia</b> (trava contra envios duplicados).
            </div>
            <x-toggle-cfg nome="aniversariantes_ativo" :ativo="(bool) $config->aniversariante_automatico" rotulo="Automatizar envio de mensagens para os Aniversariantes do dia?" dica="Chave mestra: desligada, nada é enviado" />
            <div class="pl-6 space-y-4 border-l-2 border-gray-100">
                <x-toggle-cfg nome="aniv_alunos" :ativo="$t('aniv_alunos', true)" rotulo="Enviar mensagens para aniversariantes (Alunos)?" />
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Mensagem pré-cadastrada (Alunos)</label>
                    <input type="text" name="aniv_alunos_template" value="{{ $v('aniv_alunos_template', '@ Feliz Aniversário') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <x-toggle-cfg nome="aniv_profissionais" :ativo="$t('aniv_profissionais')" rotulo="Enviar mensagens para aniversariantes (Profissionais)?" />
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Mensagem pré-cadastrada (Profissionais)</label>
                    <input type="text" name="aniv_profissionais_template" value="{{ $v('aniv_profissionais_template') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
        </div>

        {{-- ============ ABA 5: Matrícula (combo) ============ --}}
        <div x-show="aba === 4" x-cloak class="p-6 space-y-5">
            <p class="text-xs font-bold text-gray-400 uppercase">Liberação da Funcionalidade</p>
            <x-toggle-cfg nome="combo_permitir" :ativo="$t('combo_permitir')" rotulo="Permitir realizar matrícula com combo?" dica="Libera agrupar dois ou mais cursos na matrícula manual (23)" />
            <x-toggle-cfg nome="combo_crm" :ativo="$t('combo_crm')" rotulo="Permitir realizar matrícula com combo no Funil CRM?" />

            <p class="text-xs font-bold text-gray-400 uppercase pt-2">Parametrização Financeira (carnê unificado)</p>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Forma de Pagamento matrícula (Padrão) <span class="text-red-500">*</span></label>
                    <select name="combo_forma_pgto_matricula" class="w-full border rounded-lg px-3 py-2 text-sm">@foreach(['boleto' => 'Boleto Automático', 'pix' => 'PIX', 'cartao' => 'Cartão'] as $fk => $fv)<option value="{{ $fk }}" @selected($v('combo_forma_pgto_matricula', 'boleto') === $fk)>{{ $fv }}</option>@endforeach</select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Plano de conta matrícula (Padrão) <span class="text-red-500">*</span></label>
                    <select name="combo_plano_conta_matricula_id" class="w-full border rounded-lg px-3 py-2 text-sm"><option value="">Selecione...</option>@foreach($planosConta as $pc)<option value="{{ $pc->id }}" @selected($v('combo_plano_conta_matricula_id') == $pc->id)>{{ $pc->codigo }} - {{ $pc->nome }}</option>@endforeach</select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Forma de Pagamento mensalidade (Padrão) <span class="text-red-500">*</span></label>
                    <select name="combo_forma_pgto_mensalidade" class="w-full border rounded-lg px-3 py-2 text-sm">@foreach(['boleto' => 'Boleto Automático', 'pix' => 'PIX', 'cartao' => 'Cartão Recorrente'] as $fk => $fv)<option value="{{ $fk }}" @selected($v('combo_forma_pgto_mensalidade', 'boleto') === $fk)>{{ $fv }}</option>@endforeach</select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Plano de conta mensalidade (Padrão) <span class="text-red-500">*</span></label>
                    <select name="combo_plano_conta_mensalidade_id" class="w-full border rounded-lg px-3 py-2 text-sm"><option value="">Selecione...</option>@foreach($planosConta as $pc)<option value="{{ $pc->id }}" @selected($v('combo_plano_conta_mensalidade_id') == $pc->id)>{{ $pc->codigo }} - {{ $pc->nome }}</option>@endforeach</select>
                </div>
            </div>

            <p class="text-xs font-bold text-gray-400 uppercase pt-2">Regras de Conflito (quem vence entre os cursos do combo)</p>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Limitador da quantidade de parcelas <span class="text-red-500">*</span></label>
                    <select name="combo_limitador_parcelas" class="w-full border rounded-lg px-3 py-2 text-sm"><option value="maior" @selected($v('combo_limitador_parcelas', 'maior') === 'maior')>Maior</option><option value="menor" @selected($v('combo_limitador_parcelas') === 'menor')>Menor</option></select>
                    <p class="text-[11px] text-gray-400 mt-0.5">Ex.: curso A até 6x e curso B até 12x → "Maior" permite 12x no combo.</p>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Limitador do valor de desconto <span class="text-red-500">*</span></label>
                    <select name="combo_limitador_desconto" class="w-full border rounded-lg px-3 py-2 text-sm"><option value="maior" @selected($v('combo_limitador_desconto', 'maior') === 'maior')>Maior</option><option value="menor" @selected($v('combo_limitador_desconto') === 'menor')>Menor</option></select>
                </div>
            </div>
        </div>

        {{-- ============ ABA 6: Soluções Personalizadas (SOLPER) ============ --}}
        <div x-show="aba === 5" x-cloak class="p-6 space-y-4">
            <div class="bg-pink-50 border border-pink-200 rounded-lg p-3 text-xs text-pink-800">
                <b>CUIDADO:</b> alterar as configurações abaixo afeta diretamente as soluções personalizadas da sua Instituição (caso existam). A chave deve ser exata (maiúsculas/minúsculas) e o valor deve respeitar o formato esperado pela programação.
            </div>
            <div class="flex items-center justify-between">
                <button type="button" @click="solpers.push({ chave: '', valor: '' })" class="text-xs px-3 py-1.5 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50"><i class="fa-solid fa-plus mr-1"></i>Opções Personalizadas</button>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" x-model="buscaSolper" placeholder="Buscar..." class="pl-7 pr-2 py-1.5 border rounded-full text-xs w-48">
                </div>
            </div>
            <table class="w-full text-sm">
                <thead><tr class="text-left text-[11px] text-gray-400 border-b"><th class="py-1">CHAVE</th><th>VALOR</th><th class="w-12 text-right">AÇÕES</th></tr></thead>
                <tbody>
                    <template x-for="(s, i) in solpers" :key="i">
                        <tr class="border-b border-gray-50" x-show="!buscaSolper || (s.chave + s.valor).toLowerCase().includes(buscaSolper.toLowerCase())">
                            <td class="py-1 pr-2"><input type="text" :name="'solpers[' + i + '][chave]'" x-model="s.chave" class="w-full border rounded-lg px-2 py-1.5 text-sm font-mono" placeholder="SOLPER8322_..."></td>
                            <td class="pr-2"><input type="text" :name="'solpers[' + i + '][valor]'" x-model="s.valor" class="w-full border rounded-lg px-2 py-1.5 text-sm font-mono"></td>
                            <td class="text-right"><button type="button" @click="solpers.splice(i, 1)" class="w-7 h-7 border border-red-200 rounded text-red-500 hover:bg-red-50"><i class="fa-regular fa-trash-can text-xs"></i></button></td>
                        </tr>
                    </template>
                    <tr x-show="!solpers.length"><td colspan="3" class="py-6 text-center text-gray-400 text-sm">Nenhuma solução personalizada cadastrada.</td></tr>
                </tbody>
            </table>
        </div>

        <div class="sticky bottom-4 flex justify-end px-6 pb-5">
            <button type="submit" class="px-8 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30"><i class="fa-solid fa-check mr-1"></i>Salvar</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function confAcad(dados) {
    return {
        aba: 0,
        buscaSolper: '',
        apelidos: dados.apelidos || [],
        solpers: dados.solpers || [],
    };
}
</script>
@endpush
@endsection
