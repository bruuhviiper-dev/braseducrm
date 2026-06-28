@extends('layouts.app')
@section('title', 'Painel do Cliente')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <i class="fa-solid fa-display text-primary-500 text-xl"></i>
        <h1 class="text-2xl font-bold text-gray-800">Painel do Cliente</h1>
    </div>

    {{-- Institution Info --}}
    @if($instituicao)
    <div class="bg-white rounded-xl border p-6 mb-6">
        <div class="flex flex-col sm:flex-row items-start gap-6">
            <div class="w-16 h-16 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-building text-primary-600 text-2xl"></i>
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold text-gray-800">{{ $instituicao->nome }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $instituicao->razao_social }}</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mt-4">
                    @if($instituicao->cnpj)
                    <div class="text-sm">
                        <span class="text-gray-400">CNPJ:</span>
                        <span class="text-gray-700 font-medium">{{ $instituicao->cnpj }}</span>
                    </div>
                    @endif
                    @if($instituicao->email)
                    <div class="text-sm">
                        <span class="text-gray-400">Email:</span>
                        <span class="text-gray-700 font-medium">{{ $instituicao->email }}</span>
                    </div>
                    @endif
                    @if($instituicao->telefone)
                    <div class="text-sm">
                        <span class="text-gray-400">Telefone:</span>
                        <span class="text-gray-700 font-medium">{{ $instituicao->telefone }}</span>
                    </div>
                    @endif
                    @if($instituicao->endereco)
                    <div class="text-sm sm:col-span-2">
                        <span class="text-gray-400">Endereco:</span>
                        <span class="text-gray-700 font-medium">{{ $instituicao->endereco }}, {{ $instituicao->cidade }}/{{ $instituicao->uf }} - {{ $instituicao->cep }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Quick Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-user-graduate text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalAlunos }}</p>
                    <p class="text-sm text-gray-500">Total de Alunos</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-book text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalCursos }}</p>
                    <p class="text-sm text-gray-500">Total de Cursos</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-chalkboard text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalTurmasAtivas }}</p>
                    <p class="text-sm text-gray-500">Turmas Ativas</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Links Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Integracoes --}}
        <a href="{{ route('integracoes.index') }}" class="bg-white rounded-xl border p-5 hover:shadow-md hover:border-primary-200 transition group">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-indigo-200 transition">
                    <i class="fa-solid fa-plug text-indigo-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-primary-600 transition">Integracoes</h3>
                    <p class="text-sm text-gray-500 mt-1">Gerencie as integracoes com sistemas externos, APIs e webhooks.</p>
                </div>
            </div>
        </a>

        {{-- Comunicacao --}}
        <a href="{{ route('comunicacao.templates.index') }}" class="bg-white rounded-xl border p-5 hover:shadow-md hover:border-primary-200 transition group">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-yellow-200 transition">
                    <i class="fa-solid fa-comments text-yellow-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-primary-600 transition">Comunicacao</h3>
                    <p class="text-sm text-gray-500 mt-1">Configure templates de email, SMS, WhatsApp e notificacoes.</p>
                </div>
            </div>
        </a>

        {{-- Configuracoes --}}
        <a href="{{ route('configuracoes.index') }}" class="bg-white rounded-xl border p-5 hover:shadow-md hover:border-primary-200 transition group">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-gray-200 transition">
                    <i class="fa-solid fa-gear text-gray-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-primary-600 transition">Configuracoes do Sistema</h3>
                    <p class="text-sm text-gray-500 mt-1">Acesse todas as configuracoes dos modulos do sistema.</p>
                </div>
            </div>
        </a>

        {{-- Financeiro --}}
        <a href="{{ route('financeiro.fluxo-caixa.index') }}" class="bg-white rounded-xl border p-5 hover:shadow-md hover:border-primary-200 transition group">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-green-200 transition">
                    <i class="fa-solid fa-chart-line text-green-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-primary-600 transition">Resumo Financeiro</h3>
                    <p class="text-sm text-gray-500 mt-1">Visualize o fluxo de caixa, receitas e despesas da instituicao.</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
