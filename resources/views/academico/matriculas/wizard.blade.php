@extends('layouts.app')
@section('title', 'Matrícula e Histórico')

@section('content')
@php
    $op = $oportunidade;
    $catMatricula = $categorias->first(fn ($c) => stripos($c->nome, 'matr') !== false);
    $catMensalidade = $categorias->first(fn ($c) => stripos($c->nome, 'mensal') !== false);
@endphp
<div x-data="wizardMatricula()" class="max-w-6xl mx-auto">

    <div class="flex items-center gap-3 mb-4">
        <a href="{{ $op ? route('crm.funil.show', $op->funil_id) : route('academico.matriculas.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
        <div>
            <h1 class="text-lg font-bold text-gray-800">Matrícula e Histórico</h1>
            <p class="text-xs text-gray-400">Acadêmico &rsaquo; Matrícula @if($op) · a partir da oportunidade <b>{{ $op->id }} {{ $op->interessado->nome ?? '' }}</b>@endif</p>
        </div>
    </div>

    <form method="POST" action="{{ route('academico.matriculas.wizard.store') }}" class="bg-white rounded-xl border shadow-sm">
        @csrf
        @if($op)<input type="hidden" name="oportunidade_id" value="{{ $op->id }}">@endif

        {{-- Linha do tempo do assistente (4 passos) --}}
        <div class="flex items-center px-8 pt-6 pb-2">
            @foreach(['Informações básicas', 'Disciplinas iniciais', 'Financeiro', 'Conferência'] as $i => $rotulo)
            @php $n = $i + 1; @endphp
            <div class="flex items-center {{ $n < 4 ? 'flex-1' : '' }}">
                <div class="flex items-center gap-2 shrink-0">
                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold"
                          :class="passo >= {{ $n }} ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-500'">{{ $n }}</span>
                    <span class="text-sm" :class="passo === {{ $n }} ? 'text-blue-600 font-semibold' : 'text-gray-500'">{{ $rotulo }}</span>
                </div>
                @if($n < 4)<div class="flex-1 h-px bg-gray-200 mx-3"></div>@endif
            </div>
            @endforeach
        </div>

        @if($errors->any())
        <div class="mx-8 mt-3 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">
            <i class="fa-solid fa-exclamation-circle mr-1"></i>{{ $errors->first() }}
        </div>
        @endif

        {{-- ================= PASSO 1: Informações básicas ================= --}}
        <div x-show="passo === 1" class="p-8 space-y-4">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Aluno <span class="text-red-500">*</span></label>
                <select name="aluno_id" x-model="alunoId" required class="w-full border rounded-lg px-3 py-2.5 text-sm">
                    <option value="">Busque pelo nome...</option>
                    @foreach($alunos as $a)
                    <option value="{{ $a->id }}" @selected(($alunoSugerido?->id) === $a->id)>{{ $a->id }} - {{ $a->pessoa->nome ?? '-' }}</option>
                    @endforeach
                </select>
                <p class="text-[11px] text-gray-400 mt-1">Não encontrou? <a href="{{ route('alunos.create') }}" target="_blank" class="text-blue-500 hover:underline">Criar novo cadastro de aluno</a> — ao salvar, volte e recarregue esta tela para selecioná-lo.</p>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Turma Montada <span class="text-red-500">*</span></label>
                <select name="turma_montada_id" x-model="turmaId" @change="carregarDisciplinas()" required class="w-full border rounded-lg px-3 py-2.5 text-sm">
                    <option value="">Selecione a turma ativa...</option>
                    @foreach($turmas as $tm)
                    <option value="{{ $tm->id }}" @selected(($turmaSugerida?->id) === $tm->id)>{{ $tm->sigla ?: $tm->nome }} - {{ $tm->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Forma de Ingresso <span class="text-red-500">*</span></label>
                <select name="forma_ingresso_id" x-model="formaIngresso" required class="w-full border rounded-lg px-3 py-2.5 text-sm">
                    <option value="">Selecione...</option>
                    @foreach($formasIngresso as $fi)<option value="{{ $fi->id }}">{{ $fi->nome }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Como conheceu o curso?</label>
                <input type="text" name="como_conheceu" value="{{ $op->origem->nome ?? '' }}" class="w-full border rounded-lg px-3 py-2.5 text-sm" placeholder="Herdado do card do CRM">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Operador <span class="text-red-500">*</span></label>
                <div class="w-full border rounded-lg px-3 py-2.5 text-sm bg-gray-50 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-pink-100 text-pink-600 text-[10px] font-bold flex items-center justify-center">{{ collect(explode(' ', auth()->user()->nome))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('') }}</span>
                    {{ auth()->user()->nome }} <span class="text-[10px] text-gray-400 ml-1">(registrado automaticamente para auditoria)</span>
                </div>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Dia de início das aulas <span class="text-red-500">*</span></label>
                <input type="date" name="data_inicio_aulas" x-model="dataInicio" required class="w-full border rounded-lg px-3 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Tag (Opcional)</label>
                <select name="tag" class="w-full border rounded-lg px-3 py-2.5 text-sm">
                    <option value="">Selecione...</option>
                    @foreach($tags as $t)<option value="{{ $t->nome }}">{{ $t->nome }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Motivo de Ganho</label>
                <select name="motivo_ganho_id" class="w-full border rounded-lg px-3 py-2.5 text-sm">
                    <option value="">Selecione...</option>
                    @foreach($motivosGanho as $mg)<option value="{{ $mg->id }}">{{ $mg->nome }}</option>@endforeach
                </select>
            </div>
            <div>
                <input type="text" name="solucao_personalizada" placeholder="SOLPER (solução personalizada — comissão/desconto customizado)" class="w-full border rounded-lg px-3 py-2.5 text-sm">
            </div>
            <div x-data="{ n: 0 }">
                <label class="block text-xs text-gray-500 mb-1">Observação</label>
                <textarea name="observacoes" rows="3" maxlength="2000" @input="n = $event.target.value.length" class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
                <p class="text-right text-[11px] text-gray-400"><span x-text="n"></span> / 2000</p>
            </div>
        </div>

        {{-- ================= PASSO 2: Disciplinas iniciais ================= --}}
        <div x-show="passo === 2" x-cloak class="p-8">
            <div class="border rounded-lg overflow-hidden">
                <div class="flex items-center justify-between bg-gray-100 px-3 py-2">
                    <div class="flex items-center gap-2">
                        <button type="button" @click="addAberto = !addAberto" class="w-7 h-7 border bg-white rounded flex items-center justify-center text-gray-500 hover:text-blue-500" title="Adicionar disciplinas fora do bloco padrão (antecipação/DP)"><i class="fa-solid fa-plus text-xs"></i></button>
                        <span class="text-sm font-semibold text-gray-600">Disciplinas</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                            <input type="text" x-model="buscaDisc" placeholder="Buscar..." class="pl-7 pr-2 py-1.5 border rounded-full text-xs w-44">
                        </div>
                        <span class="text-xs text-gray-400" x-text="discs.length + ' itens'"></span>
                    </div>
                </div>
                <div x-show="addAberto" x-cloak class="px-3 py-2 bg-blue-50 border-b flex flex-wrap gap-1.5">
                    <template x-for="d in removidas" :key="'r' + d.id">
                        <button type="button" @click="readicionar(d)" class="text-xs px-2 py-1 bg-white border border-blue-300 text-blue-600 rounded hover:bg-blue-100"><i class="fa-solid fa-plus mr-1"></i><span x-text="d.nome"></span></button>
                    </template>
                    <span x-show="!removidas.length" class="text-xs text-blue-400">Todas as disciplinas da matriz da turma já estão no bloco inicial.</span>
                </div>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[11px] text-gray-400 border-b bg-gray-50">
                            <th class="py-2 px-3 w-8"></th><th class="py-2">DISCIPLINA</th><th>TURMA MONTADA</th><th>INÍCIO</th><th class="text-right pr-3">AÇÕES</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <template x-for="d in discsFiltradas" :key="d.id">
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-3"><input type="checkbox" checked disabled class="rounded"></td>
                                <td class="text-gray-700" x-text="d.nome"></td>
                                <td class="text-gray-500" x-text="turmaSigla()"></td>
                                <td class="text-gray-500" x-text="dataInicioBr()"></td>
                                <td class="text-right pr-3">
                                    <button type="button" @click="remover(d)" class="w-7 h-7 border border-red-200 rounded text-red-500 hover:bg-red-50" title="Dispensa de disciplina (já cursada/aprovada em outra instituição)"><i class="fa-regular fa-trash-can text-xs"></i></button>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="!discs.length"><td colspan="5" class="py-8 text-center text-gray-400 text-sm">Selecione a Turma Montada no Passo 1 para o sistema carregar a grade inicial da matriz curricular.</td></tr>
                    </tbody>
                </table>
            </div>
            <template x-for="d in discs" :key="'h' + d.id"><input type="hidden" name="disciplinas[]" :value="d.id"></template>
        </div>

        {{-- ================= PASSO 3: Financeiro ================= --}}
        <div x-show="passo === 3" x-cloak class="p-8 space-y-5">

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" x-model="gerarMat" class="sr-only peer"><span class="w-10 h-5 rounded-full bg-gray-300 peer-checked:bg-blue-500 relative transition after:content-[''] after:absolute after:w-4 after:h-4 after:bg-white after:rounded-full after:top-0.5 after:left-0.5 peer-checked:after:translate-x-5 after:transition"></span>
                <span class="text-sm font-medium text-gray-700">Gerar matrícula?</span>
            </label>
            <input type="hidden" name="gerar_matricula" :value="gerarMat ? 1 : 0">
            <div x-show="gerarMat" class="border rounded-lg">
                <p class="bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-600 rounded-t-lg">Matrícula</p>
                <div class="p-4 space-y-3">
                    <div class="grid md:grid-cols-2 gap-3">
                        <div><label class="block text-xs text-gray-500 mb-1">Valor <span class="text-red-500">*</span></label><input type="number" step="0.01" min="0" name="mat_valor" x-model="matValor" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
                        <div><label class="block text-xs text-gray-500 mb-1">Vencimento <span class="text-red-500">*</span></label><input type="date" name="mat_vencimento" x-model="matVenc" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
                    </div>
                    <div><label class="block text-xs text-gray-500 mb-1">Categoria do Título <span class="text-red-500">*</span></label>
                        <select name="mat_categoria_id" class="w-full border rounded-lg px-3 py-2 text-sm">@foreach($categorias as $c)<option value="{{ $c->id }}" @selected($catMatricula?->id === $c->id)>{{ $c->nome }}</option>@endforeach</select>
                    </div>
                    <div><label class="block text-xs text-gray-500 mb-1">Forma de Pagamento <span class="text-red-500">*</span></label>
                        <select name="mat_forma_pagamento" class="w-full border rounded-lg px-3 py-2 text-sm">
                            @foreach(['boleto' => 'Boleto Bancário', 'pix' => 'PIX', 'cartao' => 'Cartão de Crédito', 'dinheiro' => 'Dinheiro', 'transferencia' => 'Transferência', 'cheque' => 'Cheque'] as $fk => $fv)<option value="{{ $fk }}">{{ $fv }}</option>@endforeach
                        </select>
                    </div>
                    <div><label class="block text-xs text-gray-500 mb-1">Plano de Conta (Matrícula) <span class="text-red-500">*</span></label>
                        <select name="mat_plano_conta_id" x-model="matPlano" class="w-full border rounded-lg px-3 py-2 text-sm"><option value="">Selecione...</option>@foreach($planosConta as $pc)<option value="{{ $pc->id }}">{{ $pc->codigo }} - {{ $pc->nome }}</option>@endforeach</select>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer pt-1">
                        <input type="checkbox" x-model="matJuros" class="sr-only peer"><span class="w-10 h-5 rounded-full bg-gray-300 peer-checked:bg-blue-500 relative transition after:content-[''] after:absolute after:w-4 after:h-4 after:bg-white after:rounded-full after:top-0.5 after:left-0.5 peer-checked:after:translate-x-5 after:transition"></span>
                        <span class="text-sm text-gray-600">Cobrar juros e multa por atraso?</span>
                    </label>
                    <input type="hidden" name="mat_juros" :value="matJuros ? 1 : 0">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="matDescAtivo" class="sr-only peer"><span class="w-10 h-5 rounded-full bg-gray-300 peer-checked:bg-blue-500 relative transition after:content-[''] after:absolute after:w-4 after:h-4 after:bg-white after:rounded-full after:top-0.5 after:left-0.5 peer-checked:after:translate-x-5 after:transition"></span>
                        <span class="text-sm text-gray-600">Conceder desconto (Matrícula)?</span>
                    </label>
                    <div x-show="matDescAtivo"><input type="number" step="0.01" min="0" name="mat_desconto" placeholder="Desconto em R$ aplicado na taxa de matrícula" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
                </div>
            </div>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" x-model="gerarMen" class="sr-only peer"><span class="w-10 h-5 rounded-full bg-gray-300 peer-checked:bg-blue-500 relative transition after:content-[''] after:absolute after:w-4 after:h-4 after:bg-white after:rounded-full after:top-0.5 after:left-0.5 peer-checked:after:translate-x-5 after:transition"></span>
                <span class="text-sm font-medium text-gray-700">Gerar mensalidade?</span>
            </label>
            <input type="hidden" name="gerar_mensalidade" :value="gerarMen ? 1 : 0">
            <div x-show="gerarMen" class="border rounded-lg">
                <p class="bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-600 rounded-t-lg">Mensalidade</p>
                <div class="p-4 space-y-3">
                    <div class="grid md:grid-cols-2 gap-3">
                        <div><label class="block text-xs text-gray-500 mb-1">Valor <span class="text-red-500">*</span></label><input type="number" step="0.01" min="0" name="men_valor" x-model="menValor" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
                        <div><label class="block text-xs text-gray-500 mb-1">Quantidade de Parcelas <span class="text-red-500">*</span></label><input type="number" min="1" max="72" name="men_parcelas" x-model="menParcelas" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
                    </div>
                    <div><label class="block text-xs text-gray-500 mb-1">1ª Parcela <span class="text-red-500">*</span></label><input type="date" name="men_primeira" x-model="menPrimeira" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <p class="text-[11px] text-gray-400 mt-0.5">O sistema projeta automaticamente as demais parcelas nos meses seguintes pelo mesmo dia.</p>
                    </div>
                    <div><label class="block text-xs text-gray-500 mb-1">Categoria do Título <span class="text-red-500">*</span></label>
                        <select name="men_categoria_id" class="w-full border rounded-lg px-3 py-2 text-sm">@foreach($categorias as $c)<option value="{{ $c->id }}" @selected($catMensalidade?->id === $c->id)>{{ $c->nome }}</option>@endforeach</select>
                    </div>
                    <div><label class="block text-xs text-gray-500 mb-1">Forma de Pagamento <span class="text-red-500">*</span></label>
                        <select name="men_forma_pagamento" class="w-full border rounded-lg px-3 py-2 text-sm">
                            @foreach(['boleto' => 'Boleto Bancário', 'pix' => 'PIX', 'cartao' => 'Cartão de Crédito Recorrente', 'dinheiro' => 'Dinheiro', 'transferencia' => 'Transferência', 'cheque' => 'Cheque'] as $fk => $fv)<option value="{{ $fk }}">{{ $fv }}</option>@endforeach
                        </select>
                    </div>
                    <div><label class="block text-xs text-gray-500 mb-1">Plano de Conta (Mensalidade) <span class="text-red-500">*</span></label>
                        <select name="men_plano_conta_id" x-model="menPlano" class="w-full border rounded-lg px-3 py-2 text-sm"><option value="">Selecione...</option>@foreach($planosConta as $pc)<option value="{{ $pc->id }}">{{ $pc->codigo }} - {{ $pc->nome }}</option>@endforeach</select>
                    </div>
                    <div x-data="{ n: 0 }"><label class="block text-xs text-gray-500 mb-1">Instruções para o boleto</label>
                        <textarea name="men_instrucoes" rows="2" maxlength="250" @input="n = $event.target.value.length" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Sai impresso no PDF de todos os boletos deste aluno"></textarea>
                        <p class="text-right text-[11px] text-gray-400"><span x-text="n"></span> / 250</p>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="menJuros" class="sr-only peer"><span class="w-10 h-5 rounded-full bg-gray-300 peer-checked:bg-blue-500 relative transition after:content-[''] after:absolute after:w-4 after:h-4 after:bg-white after:rounded-full after:top-0.5 after:left-0.5 peer-checked:after:translate-x-5 after:transition"></span>
                        <span class="text-sm text-gray-600">Cobrar juros e multa por atraso?</span>
                    </label>
                    <input type="hidden" name="men_juros" :value="menJuros ? 1 : 0">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="menDescAtivo" class="sr-only peer"><span class="w-10 h-5 rounded-full bg-gray-300 peer-checked:bg-blue-500 relative transition after:content-[''] after:absolute after:w-4 after:h-4 after:bg-white after:rounded-full after:top-0.5 after:left-0.5 peer-checked:after:translate-x-5 after:transition"></span>
                        <span class="text-sm text-gray-600">Conceder desconto (Mensalidade)?</span>
                    </label>
                    <div x-show="menDescAtivo"><input type="number" step="0.01" min="0" name="men_desconto" placeholder="Desconto em R$ aplicado em todas as parcelas (bolsa)" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
                </div>
            </div>
        </div>

        {{-- ================= PASSO 4: Conferência ================= --}}
        <div x-show="passo === 4" x-cloak class="p-8 space-y-5">
            <div class="grid md:grid-cols-2 gap-x-8 gap-y-2 border rounded-lg p-4 bg-gray-50/60 text-sm">
                <p><span class="text-gray-400">Identificador:</span> <b class="text-gray-700" x-text="identificador()"></b></p>
                <p><span class="text-gray-400">Aluno:</span> <b class="text-gray-700" x-text="alunoNome()"></b></p>
                <p><span class="text-gray-400">Turma Montada:</span> <b class="text-gray-700" x-text="turmaNome()"></b></p>
                <p><span class="text-gray-400">Forma de Ingresso:</span> <b class="text-gray-700" x-text="ingressoNome()"></b></p>
                <p><span class="text-gray-400">Data da Matrícula:</span> <b class="text-gray-700">{{ now()->format('d/m/Y') }}</b></p>
            </div>

            <div>
                <div class="flex border-b text-sm">
                    <button type="button" @click="conferAba = 'disc'" :class="conferAba === 'disc' ? 'border-blue-500 text-blue-600 font-semibold' : 'border-transparent text-gray-500'" class="px-4 py-2 border-b-2">Disciplinas iniciais</button>
                    <button type="button" @click="conferAba = 'fin'" :class="conferAba === 'fin' ? 'border-blue-500 text-blue-600 font-semibold' : 'border-transparent text-gray-500'" class="px-4 py-2 border-b-2">Financeiro (<span x-text="(gerarMat ? 1 : 0) + (gerarMen ? 1 : 0)"></span>)</button>
                </div>
                <div x-show="conferAba === 'disc'">
                    <table class="w-full text-sm">
                        <thead><tr class="text-left text-[11px] text-gray-400 border-b"><th class="py-2">DISCIPLINA</th><th>TURMA MONTADA</th><th>INÍCIO</th></tr></thead>
                        <tbody class="divide-y">
                            <template x-for="d in discs" :key="'c' + d.id"><tr><td class="py-2 text-gray-700" x-text="d.nome"></td><td class="text-gray-500" x-text="turmaSigla()"></td><td class="text-gray-500" x-text="dataInicioBr()"></td></tr></template>
                            <tr x-show="!discs.length"><td colspan="3" class="py-5 text-center text-gray-400">Nenhuma disciplina no bloco inicial.</td></tr>
                        </tbody>
                    </table>
                </div>
                <div x-show="conferAba === 'fin'" x-cloak>
                    <table class="w-full text-sm">
                        <thead><tr class="text-left text-[11px] text-gray-400 border-b"><th class="py-2">COBRANÇA</th><th>VALOR</th><th>PARCELAS</th><th>1º VENCIMENTO</th></tr></thead>
                        <tbody class="divide-y">
                            <tr x-show="gerarMat"><td class="py-2 text-gray-700">Matrícula (taxa de adesão)</td><td class="text-gray-700" x-text="'R$ ' + (parseFloat(matValor || 0)).toFixed(2).replace('.', ',')"></td><td class="text-gray-500">1x</td><td class="text-gray-500" x-text="dataBr(matVenc)"></td></tr>
                            <tr x-show="gerarMen"><td class="py-2 text-gray-700">Mensalidade (carnê)</td><td class="text-gray-700" x-text="'R$ ' + (parseFloat(menValor || 0)).toFixed(2).replace('.', ',')"></td><td class="text-gray-500" x-text="(menParcelas || 0) + 'x'"></td><td class="text-gray-500" x-text="dataBr(menPrimeira)"></td></tr>
                            <tr x-show="!gerarMat && !gerarMen"><td colspan="4" class="py-5 text-center text-gray-400">Nenhuma cobrança será gerada.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-xs text-blue-800">
                Ao concluir: o aluno fica <b>Ativo</b> na turma, as disciplinas são liberadas, as parcelas caem no <b>Contas a Receber</b>@if($op) e o card <b>{{ $op->id }} {{ $op->interessado->nome ?? '' }}</b> é movido para a coluna <b>GANHO</b> no funil @endif.
            </div>
        </div>

        {{-- Rodapé de navegação --}}
        <div class="flex items-center justify-center gap-3 bg-gray-100 rounded-b-xl px-8 py-4">
            <button type="button" x-show="passo > 1" @click="passo--" class="px-5 py-2 border bg-white rounded-lg text-sm text-gray-600 hover:bg-gray-50"><i class="fa-solid fa-arrow-left mr-1"></i>Anterior</button>
            <button type="button" x-show="passo < 4" @click="proximo()" class="px-5 py-2 border border-blue-300 bg-white rounded-lg text-sm text-blue-600 font-semibold hover:bg-blue-50">Próximo<i class="fa-solid fa-arrow-right ml-1"></i></button>
            <button type="submit" x-show="passo === 4" x-cloak class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm font-bold"><i class="fa-solid fa-check mr-1"></i>Concluir matrícula</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
const TURMA_DISCIPLINAS = {!! $turmaDisciplinas->toJson() !!};
const TURMAS = {!! $turmas->map(fn ($tm) => ['id' => $tm->id, 'sigla' => $tm->sigla ?: $tm->nome, 'nome' => $tm->nome])->values()->toJson() !!};

function wizardMatricula() {
    return {
        passo: 1,
        alunoId: '{{ $alunoSugerido?->id ?? '' }}',
        turmaId: '{{ $turmaSugerida?->id ?? '' }}',
        formaIngresso: '',
        dataInicio: '',
        discs: [],
        removidas: [],
        buscaDisc: '',
        addAberto: false,
        conferAba: 'disc',
        gerarMat: true, matValor: '{{ $op->valor ?? '' }}', matVenc: '', matJuros: true, matDescAtivo: false, matPlano: '',
        gerarMen: true, menValor: '', menParcelas: '', menPrimeira: '', menJuros: true, menDescAtivo: false, menPlano: '',

        init() { if (this.turmaId) this.carregarDisciplinas(); },
        carregarDisciplinas() {
            this.discs = (TURMA_DISCIPLINAS[this.turmaId] || []).slice();
            this.removidas = [];
        },
        get discsFiltradas() {
            return this.discs.filter(d => !this.buscaDisc || d.nome.toLowerCase().includes(this.buscaDisc.toLowerCase()));
        },
        remover(d) { this.discs = this.discs.filter(x => x.id !== d.id); this.removidas.push(d); },
        readicionar(d) { this.removidas = this.removidas.filter(x => x.id !== d.id); this.discs.push(d); },
        turmaSigla() { return (TURMAS.find(t => t.id == this.turmaId) || {}).sigla || '-'; },
        turmaNome() { const t = TURMAS.find(t => t.id == this.turmaId); return t ? t.sigla + ' - ' + t.nome : '-'; },
        alunoNome() { const s = document.querySelector('[name=aluno_id]'); return s && s.selectedIndex > 0 ? s.options[s.selectedIndex].text : '-'; },
        ingressoNome() { const s = document.querySelector('[name=forma_ingresso_id]'); return s && s.selectedIndex > 0 ? s.options[s.selectedIndex].text : '-'; },
        identificador() { return new Date().getFullYear() + '.' + this.turmaSigla() + '.' + {{ $proximoSeq }}; },
        dataBr(v) { if (!v) return '-'; const [a, m, d] = v.split('-'); return d + '/' + m + '/' + a; },
        dataInicioBr() { return this.dataBr(this.dataInicio); },
        proximo() {
            if (this.passo === 1) {
                if (!this.alunoId || !this.turmaId || !this.formaIngresso || !this.dataInicio) { alert('Preencha os campos obrigatórios (*): Aluno, Turma Montada, Forma de Ingresso e Dia de início das aulas.'); return; }
            }
            if (this.passo === 3) {
                if (this.gerarMat && (!this.matValor || !this.matVenc || !this.matPlano)) { alert('Preencha Valor, Vencimento e Plano de Conta da Matrícula (campos com * bloqueiam o avanço).'); return; }
                if (this.gerarMen && (!this.menValor || !this.menParcelas || !this.menPrimeira || !this.menPlano)) { alert('Preencha Valor, Quantidade de Parcelas, 1ª Parcela e Plano de Conta da Mensalidade.'); return; }
            }
            this.passo++;
        },
    };
}
</script>
@endpush
@endsection
