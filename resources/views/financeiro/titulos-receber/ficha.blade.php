@extends('layouts.app')
@section('title', 'Título a Receber')

@section('content')
@php
    $pago = $titulo->situacao === 'pago';
    $baixadoPorUser = $titulo->baixado_por ? $operadores->find($titulo->baixado_por) : null;
@endphp
<div x-data="{ aba: 'dados', alterarBaixadoPor: false }" class="max-w-4xl mx-auto">

    <div class="flex items-center gap-3 mb-3">
        <a href="{{ route('financeiro.titulos-receber.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
        <div>
            <h1 class="text-lg font-bold text-gray-800"><span class="text-gray-400 font-normal">64</span> Manutenção de Títulos a Receber</h1>
            <p class="text-xs text-gray-400">Financeiro</p>
        </div>
    </div>

    @if(session('success'))<div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="mb-3 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">{{ session('error') }}</div>@endif

    {{-- Cards de resumo do recebimento (doc: ao título Baixado) --}}
    @if($pago)
    <div class="grid grid-cols-2 md:grid-cols-6 gap-3 mb-4">
        @foreach([
            ['Data do Pagamento', $titulo->data_pagamento?->format('d/m/Y'), null],
            ['Valor Nominal', 'R$ ' . number_format($titulo->valor_original, 2, ',', '.'), null],
            ['Multa', 'R$ ' . number_format($titulo->valor_multa ?? 0, 2, ',', '.'), null],
            ['Juros', 'R$ ' . number_format($titulo->valor_juros ?? 0, 2, ',', '.'), null],
            ['Desconto', 'R$ ' . number_format($titulo->valor_desconto ?? 0, 2, ',', '.'), null],
            ['Total Pago', 'R$ ' . number_format($titulo->valor_pago ?? 0, 2, ',', '.'), 'text-green-600 font-bold'],
        ] as [$lab, $val, $cls])
        <div class="bg-white border rounded-lg p-3 text-center">
            <p class="text-[10px] text-gray-400 uppercase">{{ $lab }}</p>
            <p class="text-sm {{ $cls }}">{{ $val }}</p>
        </div>
        @endforeach
    </div>
    @endif

    <div class="bg-white rounded-xl border shadow-sm">
        {{-- Faixa colorida de status --}}
        <div class="h-1.5 rounded-t-xl {{ $pago ? 'bg-green-400' : ($titulo->situacao === 'cancelado' ? 'bg-gray-400' : ($titulo->data_vencimento?->isPast() ? 'bg-red-400' : 'bg-blue-400')) }}"></div>

        {{-- Abas --}}
        <div class="flex overflow-x-auto border-b text-sm">
            @foreach(['dados' => 'Dados Básicos', 'turmas' => 'Turmas vinculadas', 'anotacoes' => 'Anotações (' . $anotacoes->count() . ')', 'restricao' => 'Restrição', 'historicos' => 'Históricos'] as $chave => $label)
            <button type="button" @click="aba = '{{ $chave }}'"
                    :class="aba === '{{ $chave }}' ? 'border-blue-500 text-blue-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2.5 border-b-2 whitespace-nowrap">{{ $label }}</button>
            @endforeach
        </div>

        {{-- ABA: Dados Básicos --}}
        <div x-show="aba === 'dados'" class="p-5">
            <form method="POST" action="{{ route('financeiro.titulos-receber.update', $titulo) }}" class="space-y-4">
                @csrf @method('PUT')

                @if($errors->any())<div class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">{{ $errors->first() }}</div>@endif

                <div class="grid md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs text-gray-500 mb-1">Aluno / Responsável Financeiro <span class="text-red-500">*</span></label>
                        <select name="pessoa_id" {{ $pago ? 'disabled' : '' }} required class="w-full border rounded-lg px-3 py-2 text-sm">
                            @foreach($pessoas as $p)<option value="{{ $p->id }}" @selected($titulo->pessoa_id == $p->id)>{{ $p->nome }} @if($p->cpf)({{ $p->cpf }})@endif</option>@endforeach
                        </select>
                        @if($pago)<input type="hidden" name="pessoa_id" value="{{ $titulo->pessoa_id }}">@endif
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Nº do Título (ID)</label>
                        <input type="text" value="{{ $titulo->id }}" disabled class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Categoria do Título</label>
                        <select name="categoria_receber_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach($categorias as $c)<option value="{{ $c->id }}" @selected($titulo->categoria_receber_id == $c->id)>{{ $c->nome }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Plano de Conta (Contabilidade)</label>
                        <select name="plano_conta_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach($planosConta as $pc)<option value="{{ $pc->id }}" @selected($titulo->plano_conta_id == $pc->id)>{{ $pc->codigo }} - {{ $pc->nome }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Valor Bruto <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" min="0.01" name="valor_original" value="{{ $titulo->valor_original }}" {{ $pago ? 'disabled' : '' }} required class="w-full border rounded-lg px-3 py-2 text-sm">
                        @if($pago)<input type="hidden" name="valor_original" value="{{ $titulo->valor_original }}">@endif
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Desconto de Pontualidade / Bolsa (R$)</label>
                        <input type="number" step="0.01" min="0" name="valor_desconto" value="{{ $titulo->valor_desconto ?? 0 }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Vencimento Original</label>
                        <input type="date" name="vencimento_original" value="{{ $titulo->vencimento_original?->format('Y-m-d') ?? $titulo->data_vencimento?->format('Y-m-d') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <p class="text-[11px] text-gray-400 mt-0.5">Mantido para auditoria quando a data for prorrogada.</p>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Vencimento <span class="text-red-500">*</span></label>
                        <input type="date" name="data_vencimento" value="{{ $titulo->data_vencimento?->format('Y-m-d') }}" {{ $pago ? 'disabled' : '' }} required class="w-full border rounded-lg px-3 py-2 text-sm">
                        @if($pago)<input type="hidden" name="data_vencimento" value="{{ $titulo->data_vencimento?->format('Y-m-d') }}">@endif
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Parcela / Total de Parcelas</label>
                        <div class="flex gap-2">
                            <input type="number" min="1" name="parcela" value="{{ $titulo->parcela }}" placeholder="Parcela" class="w-1/2 border rounded-lg px-3 py-2 text-sm">
                            <input type="number" min="1" name="total_parcelas" value="{{ $titulo->total_parcelas }}" placeholder="Total" class="w-1/2 border rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Forma de Pagamento</label>
                        <select name="forma_pagamento" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach(['boleto' => 'Boleto Bancário', 'pix' => 'PIX', 'cartao' => 'Cartão de Crédito', 'dinheiro' => 'Dinheiro', 'transferencia' => 'Transferência', 'cheque' => 'Cheque'] as $fk => $fv)
                            <option value="{{ $fk }}" @selected($titulo->forma_pagamento === $fk)>{{ $fv }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Data de Emissão <span class="text-red-500">*</span></label>
                        <input type="date" name="data_emissao" value="{{ $titulo->data_emissao?->format('Y-m-d') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div x-data="{ n: {{ mb_strlen($titulo->instrucoes_boleto ?? '') }} }" class="md:col-span-2">
                        <label class="block text-xs text-gray-500 mb-1">Instruções para o boleto</label>
                        <textarea name="instrucoes_boleto" maxlength="250" rows="2" @input="n = $event.target.value.length" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Sai impresso no PDF do boleto (Ex: Conceder desconto até o vencimento)">{{ $titulo->instrucoes_boleto }}</textarea>
                        <p class="text-right text-[11px] text-gray-400"><span x-text="n"></span> / 250</p>
                    </div>
                </div>

                <div class="border-t pt-4 space-y-2">
                    <p class="text-xs font-bold text-gray-400 uppercase mb-2">Regras de Cobrança e Integração</p>
                    <x-toggle-cfg nome="cobrar_juros_multa" :ativo="(bool) $titulo->cobrar_juros_multa" rotulo="Cobrar juros e multa por atraso?" />
                    <x-toggle-cfg nome="ocultar_portal" :ativo="(bool) $titulo->ocultar_portal" rotulo="Ocultar essa parcela no portal do aluno?" dica="Usado para taxas internas ou renegociações em andamento" />
                    <x-toggle-cfg nome="nao_emitir_nf" :ativo="(bool) $titulo->nao_emitir_nf" rotulo="Não é para emitir nota fiscal?" />
                    <x-toggle-cfg nome="apenas_nfse" :ativo="(bool) $titulo->apenas_nfse" rotulo="Emitir apenas NFS-e (sem split para NF-e)?" dica="Regras avançadas de bitributação para vendas que misturam serviço e produto" />
                </div>

                @if($pago)
                <div class="border-t pt-4">
                    <p class="text-xs font-bold text-gray-400 uppercase mb-2">Dados do Pagamento</p>
                    <div class="grid md:grid-cols-3 gap-3 text-sm">
                        <div><p class="text-xs text-gray-400">Pagador</p><p class="font-medium">{{ $titulo->pagador ?? $titulo->pessoa->nome ?? '-' }}</p></div>
                        <div><p class="text-xs text-gray-400">Responsável pela Baixa</p><p class="font-medium">{{ $baixadoPorUser?->nome ?? 'Administrador' }}</p></div>
                        <div class="flex items-end">
                            <button type="button" @click="alterarBaixadoPor = true" class="text-xs px-3 py-1.5 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50"><i class="fa-solid fa-pen mr-1"></i>Alterar responsável da baixa</button>
                        </div>
                    </div>
                </div>
                @endif

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-full text-sm font-bold shadow-sm"><i class="fa-solid fa-check mr-1"></i>Salvar</button>
                </div>
            </form>
        </div>

        {{-- ABA: Turmas vinculadas --}}
        <div x-show="aba === 'turmas'" x-cloak class="p-5">
            @if($titulo->matricula)
            <div class="border rounded-lg p-4">
                <p class="text-sm font-semibold text-gray-700">{{ $titulo->matricula->numero_matricula }}</p>
                <p class="text-xs text-gray-500 mt-1">Turma: {{ $titulo->matricula->turmaMontada?->nome ?? '-' }}</p>
                <a href="{{ route('academico.matriculas.ficha', $titulo->matricula) }}" class="inline-flex items-center gap-1 text-xs text-blue-500 hover:underline mt-2"><i class="fa-solid fa-arrow-up-right-from-square"></i>Abrir ficha da matrícula</a>
            </div>
            @else
            <p class="text-sm text-gray-400 text-center py-8">Nenhuma turma vinculada a este título.</p>
            @endif
        </div>

        {{-- ABA: Anotações --}}
        <div x-show="aba === 'anotacoes'" x-cloak class="p-5 space-y-3">
            <form method="POST" action="{{ route('financeiro.titulos-receber.anotar', $titulo) }}" class="flex gap-2">
                @csrf
                <input type="text" name="texto" required placeholder="Anotação interna sobre este título... *" class="flex-1 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-semibold">Registrar</button>
            </form>
            @forelse($anotacoes as $a)
            <div class="border rounded-lg px-4 py-3">
                <p class="text-sm text-gray-700">{{ $a->texto }}</p>
                <p class="text-[11px] text-gray-400 mt-1">{{ $a->created_at->format('d/m/Y H:i') }} · {{ $a->user?->nome ?? 'Sistema' }}</p>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-6">Nenhuma anotação registrada para este título.</p>
            @endforelse
        </div>

        {{-- ABA: Restrição --}}
        <div x-show="aba === 'restricao'" x-cloak class="p-5">
            @if($titulo->data_vencimento?->isPast() && $titulo->situacao === 'aberto')
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-sm font-semibold text-red-700"><i class="fa-solid fa-ban mr-1"></i>Título Vencido</p>
                <p class="text-xs text-red-600 mt-1">Este título está vencido desde {{ $titulo->data_vencimento->format('d/m/Y') }}. O acesso do aluno ao portal pode estar bloqueado automaticamente se a configuração de restrição por inadimplência estiver ativa.</p>
            </div>
            @elseif($titulo->ocultar_portal)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-sm font-semibold text-yellow-700"><i class="fa-solid fa-eye-slash mr-1"></i>Oculto no Portal</p>
                <p class="text-xs text-yellow-600 mt-1">Este título está oculto no portal do aluno. O saldo devedor não aparece para ele.</p>
            </div>
            @else
            <p class="text-sm text-gray-400 text-center py-8"><i class="fa-solid fa-check-circle text-green-400 text-3xl mb-2 block"></i>Sem restrições para este título.</p>
            @endif
        </div>

        {{-- ABA: Históricos --}}
        <div x-show="aba === 'historicos'" x-cloak class="p-5">
            <table class="w-full text-sm">
                <thead><tr class="text-left text-[11px] text-gray-400 border-b"><th class="py-2">EVENTO</th><th>DETALHE</th><th class="text-right">DATA</th></tr></thead>
                <tbody class="divide-y text-sm">
                    <tr><td class="py-2 text-gray-600">Emissão</td><td class="text-gray-500">R$ {{ number_format($titulo->valor_original, 2, ',', '.') }} gerado por {{ $titulo->gerado_por }}</td><td class="text-right text-gray-400">{{ $titulo->data_emissao?->format('d/m/Y') }}</td></tr>
                    @if($pago)<tr><td class="py-2 text-green-600 font-semibold">Baixa</td><td class="text-gray-500">R$ {{ number_format($titulo->valor_pago, 2, ',', '.') }} via {{ $titulo->forma_pagamento }} · Pagador: {{ $titulo->pagador ?? '-' }}</td><td class="text-right text-gray-400">{{ $titulo->data_pagamento?->format('d/m/Y') }}</td></tr>@endif
                    @if($titulo->situacao === 'cancelado')<tr><td class="py-2 text-red-600 font-semibold">Cancelamento</td><td class="text-gray-500">Inativado sem exclusão (auditoria preservada)</td><td class="text-right text-gray-400">{{ $titulo->updated_at->format('d/m/Y') }}</td></tr>@endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal: Alterar responsável pela baixa --}}
    <div x-show="alterarBaixadoPor" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" @click.self="alterarBaixadoPor = false">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-1">Alterar responsável pela baixa</h3>
            <p class="text-xs text-gray-400 mb-4">Corrija a autoria para não dar furo no caixa do funcionário que recebeu o pagamento.</p>
            <form method="POST" action="{{ route('financeiro.titulos-receber.baixado-por', $titulo) }}" class="space-y-3">
                @csrf @method('PATCH')
                <select name="baixado_por" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Selecione o operador...</option>
                    @foreach($operadores as $op)<option value="{{ $op->id }}" @selected($titulo->baixado_por == $op->id)>{{ $op->nome }}</option>@endforeach
                </select>
                <div class="flex justify-end gap-2 pt-1">
                    <button type="button" @click="alterarBaixadoPor = false" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-semibold">Alterar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Ações rápidas da barra inferior (baixa manual, estornar) --}}
    @if(!$pago && $titulo->situacao !== 'cancelado')
    <div class="mt-3 flex items-center gap-2 justify-end">
        <a href="{{ route('financeiro.titulos-receber.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Voltar à lista</a>
        <form method="POST" action="{{ route('financeiro.titulos-receber.baixar', $titulo) }}" class="inline" onsubmit="return confirm('Confirmar baixa manual do título?')">
            @csrf
            <input type="hidden" name="data_pagamento" value="{{ now()->format('Y-m-d') }}">
            <button type="submit" class="px-5 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm font-semibold"><i class="fa-solid fa-dollar-sign mr-1"></i>Baixa Manual</button>
        </form>
    </div>
    @elseif($pago)
    <div class="mt-3 flex items-center gap-2 justify-end">
        <a href="{{ route('financeiro.titulos-receber.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Voltar à lista</a>
        <form method="POST" action="{{ route('financeiro.titulos-receber.estornar', $titulo) }}" class="inline" onsubmit="return confirm('Estornar o pagamento? O título voltará a Aberto.')">
            @csrf
            <button type="submit" class="px-5 py-2 border border-orange-300 bg-orange-50 hover:bg-orange-100 text-orange-700 rounded-lg text-sm font-semibold"><i class="fa-solid fa-rotate-left mr-1"></i>Estornar Baixa</button>
        </form>
    </div>
    @endif
</div>
@endsection
