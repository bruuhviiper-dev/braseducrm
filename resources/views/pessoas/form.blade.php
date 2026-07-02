@extends('layouts.app')
@section('title', ($pessoa ?? null) ? 'Editar Pessoa' : 'Cadastro de Pessoa')

@section('content')
@php
    $telIni = ($pessoa ?? null) ? $pessoa->telefones->map(fn($t)=>['tipo'=>$t->tipo,'numero'=>$t->numero,'whatsapp'=>(bool)$t->whatsapp,'observacao'=>$t->observacao])->values() : [];
    $contaIni = ($pessoa ?? null) ? $pessoa->contas->map(fn($c)=>['banco'=>$c->banco,'agencia'=>$c->agencia,'conta'=>$c->conta,'tipo'=>$c->tipo,'chave_pix'=>$c->chave_pix,'tipo_pix'=>$c->tipo_pix])->values() : [];
    $alergiasSel = ($pessoa ?? null) ? $pessoa->alergias->pluck('id')->all() : [];
    $necessSel = ($pessoa ?? null) ? $pessoa->necessidadesEspeciais->pluck('id')->all() : [];
@endphp
<div class="max-w-6xl mx-auto" x-data="pessoaForm(@js($telIni), @js($contaIni), '{{ old('tipo', $pessoa->tipo ?? 'fisica') }}')">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <a href="{{ route('pessoas.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">11</span>
            <div>
                <h1 class="text-lg font-semibold text-gray-800">{{ ($pessoa ?? null) ? 'Editar Pessoa' : 'Cadastro de Pessoa' }}</h1>
                <p class="text-xs text-gray-400">Geral › Pessoas</p>
            </div>
        </div>

        {{-- Abas (estilo EDUQ) --}}
        <div class="border-b px-4 flex gap-1 overflow-x-auto">
            @foreach(['basicos'=>'Dados Básicos','telefones'=>'Telefones','saude'=>'Saúde','contas'=>'Contas / PIX','adicionais'=>'Infos. Adicionais','historico'=>'Histórico de Movimentações'] as $k => $t)
            <button type="button" @click="tab='{{ $k }}'" :class="tab==='{{ $k }}' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-2.5 text-sm font-medium border-b-2 whitespace-nowrap">{{ $t }}</button>
            @endforeach
        </div>

        <form method="POST" action="{{ ($pessoa ?? null) ? route('pessoas.update', $pessoa) : route('pessoas.store') }}" class="p-6">
            @csrf
            @if($pessoa ?? null) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-4">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- ================= DADOS BÁSICOS ================= --}}
            <div x-show="tab==='basicos'" class="space-y-6">
                {{-- Identificação --}}
                <div class="flex flex-wrap items-center gap-6">
                    <label class="flex items-center gap-2 text-sm"><input type="radio" name="tipo" value="fisica" x-model="tipo" class="text-primary-600"> Pessoa Física</label>
                    <label class="flex items-center gap-2 text-sm"><input type="radio" name="tipo" value="juridica" x-model="tipo" class="text-primary-600"> Pessoa Jurídica</label>
                    <label class="flex items-center gap-2 text-sm ml-auto"><input type="checkbox" name="estrangeiro" value="1" {{ old('estrangeiro', $pessoa->estrangeiro ?? false) ? 'checked' : '' }} class="rounded text-primary-600"> É estrangeiro?</label>
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="ativo" value="1" {{ old('ativo', $pessoa->ativo ?? true) ? 'checked' : '' }} class="rounded text-primary-600"> Ativo</label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div x-show="tipo==='fisica'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">CPF</label>
                        <input type="text" name="cpf" value="{{ old('cpf', $pessoa->cpf ?? '') }}" placeholder="000.000.000-00" maxlength="14" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div x-show="tipo==='juridica'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">CNPJ</label>
                        <input type="text" name="cnpj" value="{{ old('cnpj', $pessoa->cnpj ?? '') }}" placeholder="00.000.000/0000-00" maxlength="18" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1" x-text="tipo==='juridica' ? 'Razão Social *' : 'Nome de Registro *'"></label>
                        <input type="text" name="nome" value="{{ old('nome', $pessoa->nome ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" x-text="tipo==='juridica' ? 'Nome Fantasia' : 'Nome Social'"></label>
                        <input type="text" name="nome_social" value="{{ old('nome_social', $pessoa->nome_social ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div x-show="tipo==='fisica'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">RG</label>
                        <input type="text" name="rg" value="{{ old('rg', $pessoa->rg ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div x-show="tipo==='fisica'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Órgão Emissor</label>
                        <input type="text" name="orgao_emissor" value="{{ old('orgao_emissor', $pessoa->orgao_emissor ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Passaporte</label>
                        <input type="text" name="passaporte" value="{{ old('passaporte', $pessoa->passaporte ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>

                {{-- Origem / Filiação --}}
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Origem</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nacionalidade</label>
                            <input type="text" name="nacionalidade" value="{{ old('nacionalidade', $pessoa->nacionalidade ?? 'Brasileira') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Naturalidade</label>
                            <input type="text" name="naturalidade" value="{{ old('naturalidade', $pessoa->naturalidade ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Data de Nascimento</label>
                            <input type="date" name="data_nascimento" value="{{ old('data_nascimento', optional($pessoa->data_nascimento ?? null)->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div x-show="tipo==='fisica'">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gênero</label>
                            <select name="sexo" class="w-full border rounded-lg px-3 py-2 text-sm">
                                <option value="">Selecione</option>
                                @foreach(['M'=>'Masculino','F'=>'Feminino','O'=>'Outros'] as $val=>$lbl)<option value="{{ $val }}" @selected(old('sexo', $pessoa->sexo ?? '')==$val)>{{ $lbl }}</option>@endforeach
                            </select>
                        </div>
                        <div x-show="tipo==='fisica'">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estado Civil</label>
                            <select name="estado_civil" class="w-full border rounded-lg px-3 py-2 text-sm">
                                <option value="">Selecione</option>
                                @foreach(['Solteiro(a)','Casado(a)','Divorciado(a)','Viúvo(a)','União Estável','Separado(a)','Outros'] as $ec)<option value="{{ $ec }}" @selected(old('estado_civil', $pessoa->estado_civil ?? '')==$ec)>{{ $ec }}</option>@endforeach
                            </select>
                        </div>
                        <div x-show="tipo==='fisica'">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Etnia</label>
                            <select name="etnia" class="w-full border rounded-lg px-3 py-2 text-sm">
                                <option value="">Selecione</option>
                                @foreach(['Branca','Preta','Parda','Amarela','Indígena','Não declarada'] as $et)<option value="{{ $et }}" @selected(old('etnia', $pessoa->etnia ?? '')==$et)>{{ $et }}</option>@endforeach
                            </select>
                        </div>
                        <div x-show="tipo==='fisica'" class="lg:col-span-1 md:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome do pai</label>
                            <input type="text" name="nome_pai" value="{{ old('nome_pai', $pessoa->nome_pai ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div x-show="tipo==='fisica'" class="lg:col-span-2 md:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome da mãe</label>
                            <input type="text" name="nome_mae" value="{{ old('nome_mae', $pessoa->nome_mae ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                </div>

                {{-- Endereço --}}
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Endereço</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CEP</label>
                            <input type="text" name="cep" value="{{ old('cep', $pessoa->cep ?? '') }}" placeholder="00000-000" maxlength="9" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rua / Logradouro</label>
                            <input type="text" name="endereco" value="{{ old('endereco', $pessoa->endereco ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Número</label>
                            <input type="text" name="numero" value="{{ old('numero', $pessoa->numero ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Complemento</label>
                            <input type="text" name="complemento" value="{{ old('complemento', $pessoa->complemento ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
                            <input type="text" name="bairro" value="{{ old('bairro', $pessoa->bairro ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Caixa Postal</label>
                            <input type="text" name="caixa_postal" value="{{ old('caixa_postal', $pessoa->caixa_postal ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cidade</label>
                            <input type="text" name="cidade" value="{{ old('cidade', $pessoa->cidade ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">UF</label>
                            <select name="uf" class="w-full border rounded-lg px-3 py-2 text-sm">
                                <option value="">—</option>
                                @foreach(['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf)<option value="{{ $uf }}" @selected(old('uf', $pessoa->uf ?? '')==$uf)>{{ $uf }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">País</label>
                            <input type="text" name="pais" value="{{ old('pais', $pessoa->pais ?? 'Brasil') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                </div>

                {{-- Contato --}}
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Contato</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Celular</label>
                            <input type="text" name="celular" value="{{ old('celular', $pessoa->celular ?? '') }}" placeholder="(00) 00000-0000" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                            <input type="text" name="telefone" value="{{ old('telefone', $pessoa->telefone ?? '') }}" placeholder="(00) 0000-0000" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                            <input type="email" name="email" value="{{ old('email', $pessoa->email ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Instagram</label>
                            <input type="text" name="instagram" value="{{ old('instagram', $pessoa->instagram ?? '') }}" placeholder="usuario" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Facebook</label>
                            <input type="text" name="facebook" value="{{ old('facebook', $pessoa->facebook ?? '') }}" placeholder="usuario" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">LinkedIn</label>
                            <input type="text" name="linkedin" value="{{ old('linkedin', $pessoa->linkedin ?? '') }}" placeholder="usuario" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                </div>

                {{-- Profissão --}}
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Profissão</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Profissão</label>
                            <select name="profissao_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                                <option value="">Selecione</option>
                                @foreach($profissoes as $p)<option value="{{ $p->id }}" @selected(old('profissao_id', $pessoa->profissao_id ?? '')==$p->id)>{{ $p->nome }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Local de trabalho</label>
                            <input type="text" name="local_trabalho" value="{{ old('local_trabalho', $pessoa->local_trabalho ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nº conselho de classe</label>
                            <input type="text" name="numero_conselho" value="{{ old('numero_conselho', $pessoa->numero_conselho ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Currículo Lattes (iD)</label>
                            <input type="text" name="lattes" value="{{ old('lattes', $pessoa->lattes ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Religião</label>
                            <select name="religiao_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                                <option value="">Selecione</option>
                                @foreach($religioes as $r)<option value="{{ $r->id }}" @selected(old('religiao_id', $pessoa->religiao_id ?? '')==$r->id)>{{ $r->nome }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Escola de origem</label>
                            <select name="escola_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                                <option value="">Selecione</option>
                                @foreach($escolas as $e)<option value="{{ $e->id }}" @selected(old('escola_id', $pessoa->escola_id ?? '')==$e->id)>{{ $e->nome }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= TELEFONES ================= --}}
            <div x-show="tab==='telefones'" x-cloak class="space-y-3">
                <div class="flex justify-between items-center">
                    <p class="text-sm text-gray-500">Telefones adicionais da pessoa.</p>
                    <button type="button" @click="addTel()" class="px-3 py-1.5 bg-primary-600 text-white rounded-lg text-xs font-medium hover:bg-primary-700"><i class="fa-solid fa-plus mr-1"></i> Telefone</button>
                </div>
                <template x-for="(t,i) in tels" :key="i">
                    <div class="border rounded-lg p-3 bg-gray-50 grid grid-cols-1 md:grid-cols-6 gap-2 items-center">
                        <select :name="`telefones[${i}][tipo]`" x-model="t.tipo" class="border rounded px-2 py-1.5 text-sm">
                            <option value="">Tipo...</option>
                            <option value="celular">Celular</option>
                            <option value="residencial">Residencial</option>
                            <option value="comercial">Comercial</option>
                            <option value="recado">Recado</option>
                        </select>
                        <input type="text" :name="`telefones[${i}][numero]`" x-model="t.numero" placeholder="Número" class="border rounded px-2 py-1.5 text-sm md:col-span-2">
                        <input type="text" :name="`telefones[${i}][observacao]`" x-model="t.observacao" placeholder="Observação" class="border rounded px-2 py-1.5 text-sm md:col-span-2">
                        <div class="flex items-center gap-2">
                            <label class="flex items-center gap-1 text-xs"><input type="checkbox" :name="`telefones[${i}][whatsapp]`" value="1" x-model="t.whatsapp" class="rounded text-green-600"> <i class="fa-brands fa-whatsapp text-green-500"></i></label>
                            <button type="button" @click="tels.splice(i,1)" class="p-2 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                </template>
                <p x-show="tels.length===0" class="text-xs text-gray-400 py-2">Nenhum telefone adicional.</p>
            </div>

            {{-- ================= SAÚDE ================= --}}
            <div x-show="tab==='saude'" x-cloak class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-2">Alergias</p>
                        <div class="border rounded-lg p-3 max-h-56 overflow-y-auto space-y-1.5">
                            @forelse($alergias as $a)
                            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="alergias[]" value="{{ $a->id }}" @checked(in_array($a->id, old('alergias', $alergiasSel))) class="rounded text-primary-600"> {{ $a->nome }}</label>
                            @empty
                            <p class="text-xs text-gray-400">Nenhuma alergia cadastrada.</p>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-2">Necessidades especiais</p>
                        <div class="border rounded-lg p-3 max-h-56 overflow-y-auto space-y-1.5">
                            @forelse($necessidades as $n)
                            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="necessidades[]" value="{{ $n->id }}" @checked(in_array($n->id, old('necessidades', $necessSel))) class="rounded text-primary-600"> {{ $n->nome }}</label>
                            @empty
                            <p class="text-xs text-gray-400">Nenhuma necessidade cadastrada.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observações de saúde</label>
                    <textarea name="observacoes_saude" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('observacoes_saude', $pessoa->observacoes_saude ?? '') }}</textarea>
                </div>
            </div>

            {{-- ================= CONTAS / PIX ================= --}}
            <div x-show="tab==='contas'" x-cloak class="space-y-3">
                <div class="flex justify-between items-center">
                    <p class="text-sm text-gray-500">Contas bancárias e chaves PIX.</p>
                    <button type="button" @click="addConta()" class="px-3 py-1.5 bg-primary-600 text-white rounded-lg text-xs font-medium hover:bg-primary-700"><i class="fa-solid fa-plus mr-1"></i> Conta</button>
                </div>
                <template x-for="(c,i) in contas" :key="i">
                    <div class="border rounded-lg p-3 bg-gray-50 grid grid-cols-1 md:grid-cols-6 gap-2 items-center">
                        <input type="text" :name="`contas[${i}][banco]`" x-model="c.banco" placeholder="Banco" class="border rounded px-2 py-1.5 text-sm">
                        <input type="text" :name="`contas[${i}][agencia]`" x-model="c.agencia" placeholder="Agência" class="border rounded px-2 py-1.5 text-sm">
                        <input type="text" :name="`contas[${i}][conta]`" x-model="c.conta" placeholder="Conta" class="border rounded px-2 py-1.5 text-sm">
                        <select :name="`contas[${i}][tipo_pix]`" x-model="c.tipo_pix" class="border rounded px-2 py-1.5 text-sm">
                            <option value="">Tipo PIX...</option>
                            <option value="cpf">CPF/CNPJ</option>
                            <option value="email">E-mail</option>
                            <option value="telefone">Telefone</option>
                            <option value="aleatoria">Aleatória</option>
                        </select>
                        <input type="text" :name="`contas[${i}][chave_pix]`" x-model="c.chave_pix" placeholder="Chave PIX" class="border rounded px-2 py-1.5 text-sm">
                        <button type="button" @click="contas.splice(i,1)" class="p-2 text-red-600 hover:bg-red-50 rounded justify-self-end"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </template>
                <p x-show="contas.length===0" class="text-xs text-gray-400 py-2">Nenhuma conta cadastrada.</p>
            </div>

            {{-- ================= INFOS. ADICIONAIS ================= --}}
            <div x-show="tab==='adicionais'" x-cloak class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Informações Adicionais</label>
                    <textarea name="observacoes" rows="4" maxlength="2000" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('observacoes', $pessoa->observacoes ?? '') }}</textarea>
                </div>
                <div class="space-y-2 border-t pt-4">
                    <label class="flex items-start gap-2 text-sm"><input type="checkbox" name="nao_receber_mensagens" value="1" {{ old('nao_receber_mensagens', $pessoa->nao_receber_mensagens ?? false) ? 'checked' : '' }} class="mt-0.5 rounded text-primary-600"> <span>A pessoa não deseja mais receber mensagens da instituição <span class="text-gray-400">(Mensagens Avulsas, Aviso de Vencimento, Aviso de Cobrança)</span></span></label>
                    <label class="flex items-start gap-2 text-sm"><input type="checkbox" name="ignorar_reajuste" value="1" {{ old('ignorar_reajuste', $pessoa->ignorar_reajuste ?? false) ? 'checked' : '' }} class="mt-0.5 rounded text-primary-600"> <span>Ignorar pessoa no reajuste por índice <span class="text-gray-400">(Função 175 - Atualização de Parcelas pelo Índice)</span></span></label>
                    <label class="flex items-start gap-2 text-sm"><input type="checkbox" name="blacklist" value="1" {{ old('blacklist', $pessoa->blacklist ?? false) ? 'checked' : '' }} class="mt-0.5 rounded text-red-600"> <span>Está na blacklist?</span></label>
                </div>
            </div>

            {{-- ================= HISTÓRICO DE MOVIMENTAÇÕES ================= --}}
            <div x-show="tab==='historico'" x-cloak>
                @if(($pessoa ?? null) && $pessoa->aluno && $pessoa->aluno->matriculas->count())
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b"><tr>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Matrícula</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Turma</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Situação</th>
                    </tr></thead>
                    <tbody class="divide-y">
                        @foreach($pessoa->aluno->matriculas as $m)
                        <tr><td class="px-4 py-2">{{ $m->numero_matricula ?? $m->id }}</td><td class="px-4 py-2">{{ $m->turma?->nome ?? '—' }}</td><td class="px-4 py-2">{{ ucfirst($m->situacao ?? '—') }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-sm text-gray-400 py-4 text-center">Nenhuma movimentação registrada.</p>
                @endif
            </div>

            <div class="flex justify-end gap-3 pt-4 mt-4 border-t">
                <a href="{{ route('pessoas.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
function pessoaForm(telIni, contaIni, tipoIni) {
    return {
        tab: 'basicos',
        tipo: tipoIni || 'fisica',
        tels: (telIni||[]).map(t=>({tipo:t.tipo??'',numero:t.numero??'',whatsapp:!!t.whatsapp,observacao:t.observacao??''})),
        contas: (contaIni||[]).map(c=>({banco:c.banco??'',agencia:c.agencia??'',conta:c.conta??'',tipo:c.tipo??'',chave_pix:c.chave_pix??'',tipo_pix:c.tipo_pix??''})),
        addTel() { this.tels.push({tipo:'celular',numero:'',whatsapp:false,observacao:''}); },
        addConta() { this.contas.push({banco:'',agencia:'',conta:'',tipo:'',chave_pix:'',tipo_pix:''}); },
    };
}
</script>
@endsection
