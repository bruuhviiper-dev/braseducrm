@extends('layouts.app')
@section('title', isset($pessoa) ? 'Editar Pessoa' : 'Nova Pessoa')

@section('content')
<div class="bg-white rounded-xl border" x-data="{ activeTab: 'pessoais', tipo: '{{ old('tipo', $pessoa->tipo ?? 'fisica') }}' }">
    <div class="p-5 border-b flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">11</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ isset($pessoa) ? 'Editar Pessoa' : 'Nova Pessoa' }}</h1>
        </div>
    </div>

    <form method="POST" action="{{ isset($pessoa) ? route('pessoas.update', $pessoa) : route('pessoas.store') }}">
        @csrf
        @if(isset($pessoa))
            @method('PUT')
        @endif

        {{-- Tabs Navigation --}}
        <div class="border-b px-5">
            <nav class="flex gap-6 -mb-px">
                <button type="button" @click="activeTab = 'pessoais'"
                        :class="activeTab === 'pessoais' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="py-3 border-b-2 text-sm font-medium transition">
                    <i class="fa-solid fa-user mr-1"></i> Dados Pessoais
                </button>
                <button type="button" @click="activeTab = 'endereco'"
                        :class="activeTab === 'endereco' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="py-3 border-b-2 text-sm font-medium transition">
                    <i class="fa-solid fa-map-marker-alt mr-1"></i> Endereco
                </button>
                <button type="button" @click="activeTab = 'complementar'"
                        :class="activeTab === 'complementar' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="py-3 border-b-2 text-sm font-medium transition">
                    <i class="fa-solid fa-circle-info mr-1"></i> Complementar
                </button>
            </nav>
        </div>

        <div class="p-5">
            {{-- Validation Errors --}}
            @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                <ul class="text-sm text-red-600 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Tab: Dados Pessoais --}}
            <div x-show="activeTab === 'pessoais'" x-cloak>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    {{-- Tipo --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                        <select name="tipo" x-model="tipo"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                            <option value="fisica">Pessoa Fisica</option>
                            <option value="juridica">Pessoa Juridica</option>
                        </select>
                    </div>

                    {{-- Nome --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                        <input type="text" name="nome" value="{{ old('nome', $pessoa->nome ?? '') }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none" required>
                    </div>

                    {{-- Nome Social --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nome Social</label>
                        <input type="text" name="nome_social" value="{{ old('nome_social', $pessoa->nome_social ?? '') }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    </div>

                    {{-- CPF (Pessoa Fisica) --}}
                    <div x-show="tipo === 'fisica'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">CPF</label>
                        <input type="text" name="cpf" value="{{ old('cpf', $pessoa->cpf ?? '') }}"
                               placeholder="000.000.000-00" maxlength="14"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    </div>

                    {{-- CNPJ (Pessoa Juridica) --}}
                    <div x-show="tipo === 'juridica'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">CNPJ</label>
                        <input type="text" name="cnpj" value="{{ old('cnpj', $pessoa->cnpj ?? '') }}"
                               placeholder="00.000.000/0000-00" maxlength="18"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    </div>

                    {{-- RG --}}
                    <div x-show="tipo === 'fisica'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">RG</label>
                        <input type="text" name="rg" value="{{ old('rg', $pessoa->rg ?? '') }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    </div>

                    {{-- Orgao Emissor --}}
                    <div x-show="tipo === 'fisica'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Orgao Emissor</label>
                        <input type="text" name="orgao_emissor" value="{{ old('orgao_emissor', $pessoa->orgao_emissor ?? '') }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    </div>

                    {{-- Data Nascimento --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Data de Nascimento</label>
                        <input type="date" name="data_nascimento"
                               value="{{ old('data_nascimento', isset($pessoa) && $pessoa->data_nascimento ? $pessoa->data_nascimento->format('Y-m-d') : '') }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    </div>

                    {{-- Sexo --}}
                    <div x-show="tipo === 'fisica'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sexo</label>
                        <select name="sexo" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                            <option value="">Selecione</option>
                            <option value="M" {{ old('sexo', $pessoa->sexo ?? '') == 'M' ? 'selected' : '' }}>Masculino</option>
                            <option value="F" {{ old('sexo', $pessoa->sexo ?? '') == 'F' ? 'selected' : '' }}>Feminino</option>
                            <option value="O" {{ old('sexo', $pessoa->sexo ?? '') == 'O' ? 'selected' : '' }}>Outro</option>
                        </select>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $pessoa->email ?? '') }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    </div>

                    {{-- Telefone --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                        <input type="text" name="telefone" value="{{ old('telefone', $pessoa->telefone ?? '') }}"
                               placeholder="(00) 0000-0000"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    </div>

                    {{-- Celular --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Celular</label>
                        <input type="text" name="celular" value="{{ old('celular', $pessoa->celular ?? '') }}"
                               placeholder="(00) 00000-0000"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    </div>

                    {{-- Ativo --}}
                    <div class="flex items-center pt-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="ativo" value="1"
                                   {{ old('ativo', $pessoa->ativo ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                            <span class="text-sm font-medium text-gray-700">Ativo</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Tab: Endereco --}}
            <div x-show="activeTab === 'endereco'" x-cloak>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CEP</label>
                        <input type="text" name="cep" value="{{ old('cep', $pessoa->cep ?? '') }}"
                               placeholder="00000-000" maxlength="9"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    </div>

                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Endereco</label>
                        <input type="text" name="endereco" value="{{ old('endereco', $pessoa->endereco ?? '') }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Numero</label>
                        <input type="text" name="numero" value="{{ old('numero', $pessoa->numero ?? '') }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Complemento</label>
                        <input type="text" name="complemento" value="{{ old('complemento', $pessoa->complemento ?? '') }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
                        <input type="text" name="bairro" value="{{ old('bairro', $pessoa->bairro ?? '') }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cidade</label>
                        <input type="text" name="cidade" value="{{ old('cidade', $pessoa->cidade ?? '') }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">UF</label>
                        <select name="uf" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                            <option value="">Selecione</option>
                            @foreach(['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf)
                                <option value="{{ $uf }}" {{ old('uf', $pessoa->uf ?? '') == $uf ? 'selected' : '' }}>{{ $uf }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pais</label>
                        <input type="text" name="pais" value="{{ old('pais', $pessoa->pais ?? 'Brasil') }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    </div>
                </div>
            </div>

            {{-- Tab: Complementar --}}
            <div x-show="activeTab === 'complementar'" x-cloak>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Religiao</label>
                        <select name="religiao_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                            <option value="">Selecione</option>
                            @foreach($religioes as $religiao)
                                <option value="{{ $religiao->id }}" {{ old('religiao_id', $pessoa->religiao_id ?? '') == $religiao->id ? 'selected' : '' }}>
                                    {{ $religiao->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Profissao</label>
                        <select name="profissao_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                            <option value="">Selecione</option>
                            @foreach($profissoes as $profissao)
                                <option value="{{ $profissao->id }}" {{ old('profissao_id', $pessoa->profissao_id ?? '') == $profissao->id ? 'selected' : '' }}>
                                    {{ $profissao->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Escola</label>
                        <select name="escola_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                            <option value="">Selecione</option>
                            @foreach($escolas as $escola)
                                <option value="{{ $escola->id }}" {{ old('escola_id', $pessoa->escola_id ?? '') == $escola->id ? 'selected' : '' }}>
                                    {{ $escola->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado Civil</label>
                        <select name="estado_civil" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                            <option value="">Selecione</option>
                            @foreach(['Solteiro(a)', 'Casado(a)', 'Divorciado(a)', 'Viuvo(a)', 'Uniao Estavel', 'Separado(a)'] as $ec)
                                <option value="{{ $ec }}" {{ old('estado_civil', $pessoa->estado_civil ?? '') == $ec ? 'selected' : '' }}>{{ $ec }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nacionalidade</label>
                        <input type="text" name="nacionalidade" value="{{ old('nacionalidade', $pessoa->nacionalidade ?? 'Brasileira') }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Naturalidade</label>
                        <input type="text" name="naturalidade" value="{{ old('naturalidade', $pessoa->naturalidade ?? '') }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    </div>

                    <div class="lg:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Observacoes</label>
                        <textarea name="observacoes" rows="3"
                                  class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">{{ old('observacoes', $pessoa->observacoes ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 mt-6 pt-4 border-t">
                <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-primary-700 transition flex items-center gap-2">
                    <i class="fa-solid fa-check"></i> Salvar
                </button>
                <a href="{{ route('pessoas.index') }}" class="px-6 py-2 border rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                    Cancelar
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
