@extends('layouts.app')
@section('title', 'Pessoa - ' . $pessoa->nome)

@section('content')
<div class="bg-white rounded-xl border" x-data="{ activeTab: 'dados' }">
    <div class="p-5 border-b flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">11</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $pessoa->nome }}</h1>
            @if($pessoa->ativo)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Ativo</span>
            @else
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Inativo</span>
            @endif
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('pessoas.edit', $pessoa) }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-700 transition flex items-center gap-2">
                <i class="fa-solid fa-pen"></i> Editar
            </a>
            <a href="{{ route('pessoas.index') }}" class="px-4 py-2 border rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                Voltar
            </a>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="border-b px-5">
        <nav class="flex gap-6 -mb-px">
            <button type="button" @click="activeTab = 'dados'"
                    :class="activeTab === 'dados' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="py-3 border-b-2 text-sm font-medium transition">
                <i class="fa-solid fa-user mr-1"></i> Dados
            </button>
            <button type="button" @click="activeTab = 'aluno'"
                    :class="activeTab === 'aluno' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="py-3 border-b-2 text-sm font-medium transition">
                <i class="fa-solid fa-graduation-cap mr-1"></i> Vinculo Aluno
            </button>
            <button type="button" @click="activeTab = 'profissional'"
                    :class="activeTab === 'profissional' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="py-3 border-b-2 text-sm font-medium transition">
                <i class="fa-solid fa-briefcase mr-1"></i> Vinculo Profissional
            </button>
            <button type="button" @click="activeTab = 'historico'"
                    :class="activeTab === 'historico' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="py-3 border-b-2 text-sm font-medium transition">
                <i class="fa-solid fa-clock-rotate-left mr-1"></i> Historico
            </button>
        </nav>
    </div>

    <div class="p-5">
        {{-- Tab: Dados --}}
        <div x-show="activeTab === 'dados'" x-cloak>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Dados Pessoais --}}
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-id-card text-primary-500"></i> Dados Pessoais
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Tipo:</span>
                            <span class="font-medium text-gray-800">{{ $pessoa->tipo == 'fisica' ? 'Pessoa Fisica' : 'Pessoa Juridica' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Nome:</span>
                            <span class="font-medium text-gray-800">{{ $pessoa->nome }}</span>
                        </div>
                        @if($pessoa->nome_social)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Nome Social:</span>
                            <span class="font-medium text-gray-800">{{ $pessoa->nome_social }}</span>
                        </div>
                        @endif
                        @if($pessoa->cpf)
                        <div class="flex justify-between">
                            <span class="text-gray-500">CPF:</span>
                            <span class="font-medium text-gray-800">{{ $pessoa->cpf }}</span>
                        </div>
                        @endif
                        @if($pessoa->cnpj)
                        <div class="flex justify-between">
                            <span class="text-gray-500">CNPJ:</span>
                            <span class="font-medium text-gray-800">{{ $pessoa->cnpj }}</span>
                        </div>
                        @endif
                        @if($pessoa->rg)
                        <div class="flex justify-between">
                            <span class="text-gray-500">RG:</span>
                            <span class="font-medium text-gray-800">{{ $pessoa->rg }} {{ $pessoa->orgao_emissor ? '/ ' . $pessoa->orgao_emissor : '' }}</span>
                        </div>
                        @endif
                        @if($pessoa->data_nascimento)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Data Nascimento:</span>
                            <span class="font-medium text-gray-800">{{ $pessoa->data_nascimento->format('d/m/Y') }}</span>
                        </div>
                        @endif
                        @if($pessoa->sexo)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Sexo:</span>
                            <span class="font-medium text-gray-800">{{ $pessoa->sexo == 'M' ? 'Masculino' : ($pessoa->sexo == 'F' ? 'Feminino' : 'Outro') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Contato --}}
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-phone text-primary-500"></i> Contato
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Email:</span>
                            <span class="font-medium text-gray-800">{{ $pessoa->email ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Telefone:</span>
                            <span class="font-medium text-gray-800">{{ $pessoa->telefone ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Celular:</span>
                            <span class="font-medium text-gray-800">{{ $pessoa->celular ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Endereco --}}
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-map-marker-alt text-primary-500"></i> Endereco
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">CEP:</span>
                            <span class="font-medium text-gray-800">{{ $pessoa->cep ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Endereco:</span>
                            <span class="font-medium text-gray-800">
                                {{ $pessoa->endereco ?? '-' }}{{ $pessoa->numero ? ', ' . $pessoa->numero : '' }}
                                {{ $pessoa->complemento ? ' - ' . $pessoa->complemento : '' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Bairro:</span>
                            <span class="font-medium text-gray-800">{{ $pessoa->bairro ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Cidade/UF:</span>
                            <span class="font-medium text-gray-800">
                                {{ $pessoa->cidade ?? '-' }}{{ $pessoa->uf ? '/' . $pessoa->uf : '' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Pais:</span>
                            <span class="font-medium text-gray-800">{{ $pessoa->pais ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Complementar --}}
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-primary-500"></i> Complementar
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Religiao:</span>
                            <span class="font-medium text-gray-800">{{ $pessoa->religiao->nome ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Profissao:</span>
                            <span class="font-medium text-gray-800">{{ $pessoa->profissao->nome ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Escola:</span>
                            <span class="font-medium text-gray-800">{{ $pessoa->escola->nome ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Estado Civil:</span>
                            <span class="font-medium text-gray-800">{{ $pessoa->estado_civil ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Nacionalidade:</span>
                            <span class="font-medium text-gray-800">{{ $pessoa->nacionalidade ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Naturalidade:</span>
                            <span class="font-medium text-gray-800">{{ $pessoa->naturalidade ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Observacoes --}}
                @if($pessoa->observacoes)
                <div class="lg:col-span-2 bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-sticky-note text-primary-500"></i> Observacoes
                    </h3>
                    <p class="text-sm text-gray-700">{{ $pessoa->observacoes }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Tab: Vinculo Aluno --}}
        <div x-show="activeTab === 'aluno'" x-cloak>
            @if($pessoa->aluno)
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">RA:</span>
                        <span class="font-medium text-gray-800">{{ $pessoa->aluno->ra ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Forma de Ingresso:</span>
                        <span class="font-medium text-gray-800">{{ $pessoa->aluno->formaIngresso->nome ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Data de Ingresso:</span>
                        <span class="font-medium text-gray-800">{{ $pessoa->aluno->data_ingresso ? $pessoa->aluno->data_ingresso->format('d/m/Y') : '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Ativo:</span>
                        @if($pessoa->aluno->ativo)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Sim</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Nao</span>
                        @endif
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-8 text-gray-400">
                <i class="fa-solid fa-graduation-cap text-3xl mb-2"></i>
                <p class="text-sm">Esta pessoa nao possui vinculo como aluno.</p>
            </div>
            @endif
        </div>

        {{-- Tab: Vinculo Profissional --}}
        <div x-show="activeTab === 'profissional'" x-cloak>
            @if($pessoa->profissional)
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">ID:</span>
                        <span class="font-medium text-gray-800">{{ $pessoa->profissional->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Ativo:</span>
                        @if($pessoa->profissional->ativo)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Sim</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Nao</span>
                        @endif
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-8 text-gray-400">
                <i class="fa-solid fa-briefcase text-3xl mb-2"></i>
                <p class="text-sm">Esta pessoa nao possui vinculo como profissional.</p>
            </div>
            @endif
        </div>

        {{-- Tab: Historico --}}
        <div x-show="activeTab === 'historico'" x-cloak>
            <div class="space-y-3">
                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-plus text-primary-600 text-xs"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Cadastro realizado</p>
                        <p class="text-xs text-gray-500">{{ $pessoa->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                @if($pessoa->updated_at->ne($pessoa->created_at))
                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-pen text-yellow-600 text-xs"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Ultima atualizacao</p>
                        <p class="text-xs text-gray-500">{{ $pessoa->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
