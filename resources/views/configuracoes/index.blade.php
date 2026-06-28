@extends('layouts.app')
@section('title', 'Configuracoes')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <i class="fa-solid fa-gear text-primary-500 text-xl"></i>
        <h1 class="text-2xl font-bold text-gray-800">Configuracoes</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {{-- Configuracao do Academico --}}
        <a href="#" class="bg-white rounded-xl border p-5 hover:shadow-md hover:border-primary-200 transition group">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-blue-200 transition">
                    <i class="fa-solid fa-graduation-cap text-blue-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-primary-600 transition">Configuracao do Academico</h3>
                    <p class="text-sm text-gray-500 mt-1">Parametros gerais do modulo academico, periodos letivos e avaliacoes.</p>
                    <span class="text-xs text-gray-400 mt-2 inline-block">Funcao 167</span>
                </div>
            </div>
        </a>

        {{-- Configuracao do Financeiro --}}
        <a href="#" class="bg-white rounded-xl border p-5 hover:shadow-md hover:border-primary-200 transition group">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-green-200 transition">
                    <i class="fa-solid fa-dollar-sign text-green-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-primary-600 transition">Configuracao do Financeiro</h3>
                    <p class="text-sm text-gray-500 mt-1">Contas bancarias, categorias financeiras, regras de boleto e cobranca.</p>
                    <span class="text-xs text-gray-400 mt-2 inline-block">Funcao 59</span>
                </div>
            </div>
        </a>

        {{-- Configuracao do CRM --}}
        <a href="#" class="bg-white rounded-xl border p-5 hover:shadow-md hover:border-primary-200 transition group">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-purple-200 transition">
                    <i class="fa-solid fa-handshake text-purple-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-primary-600 transition">Configuracao do CRM</h3>
                    <p class="text-sm text-gray-500 mt-1">Funis, origens, tags, categorias e regras de automacao do CRM.</p>
                    <span class="text-xs text-gray-400 mt-2 inline-block">Funcao 166</span>
                </div>
            </div>
        </a>

        {{-- Configuracao da Comunicacao --}}
        <a href="#" class="bg-white rounded-xl border p-5 hover:shadow-md hover:border-primary-200 transition group">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-yellow-200 transition">
                    <i class="fa-solid fa-comments text-yellow-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-primary-600 transition">Configuracao da Comunicacao</h3>
                    <p class="text-sm text-gray-500 mt-1">Templates de email, SMS, WhatsApp e notificacoes automaticas.</p>
                    <span class="text-xs text-gray-400 mt-2 inline-block">Funcao 85</span>
                </div>
            </div>
        </a>

        {{-- Cadastro de Operador --}}
        <a href="#" class="bg-white rounded-xl border p-5 hover:shadow-md hover:border-primary-200 transition group">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-indigo-200 transition">
                    <i class="fa-solid fa-user-gear text-indigo-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-primary-600 transition">Cadastro de Operador</h3>
                    <p class="text-sm text-gray-500 mt-1">Gerenciar operadores, logins, senhas e permissoes de acesso.</p>
                    <span class="text-xs text-gray-400 mt-2 inline-block">Funcao 44</span>
                </div>
            </div>
        </a>

        {{-- Cadastro de Grupo de Operadores --}}
        <a href="#" class="bg-white rounded-xl border p-5 hover:shadow-md hover:border-primary-200 transition group">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-teal-200 transition">
                    <i class="fa-solid fa-users-gear text-teal-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-primary-600 transition">Cadastro de Grupo de Operadores</h3>
                    <p class="text-sm text-gray-500 mt-1">Perfis de acesso, grupos e niveis de permissao do sistema.</p>
                    <span class="text-xs text-gray-400 mt-2 inline-block">Funcao 43</span>
                </div>
            </div>
        </a>

        {{-- Configuracao Portal Aluno --}}
        <a href="#" class="bg-white rounded-xl border p-5 hover:shadow-md hover:border-primary-200 transition group">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-orange-200 transition">
                    <i class="fa-solid fa-desktop text-orange-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-primary-600 transition">Configuracao Portal Aluno</h3>
                    <p class="text-sm text-gray-500 mt-1">Menus, funcionalidades e aparencia do portal do aluno.</p>
                    <span class="text-xs text-gray-400 mt-2 inline-block">Funcao 46</span>
                </div>
            </div>
        </a>

        {{-- Configuracao Portal de Inscricao --}}
        <a href="#" class="bg-white rounded-xl border p-5 hover:shadow-md hover:border-primary-200 transition group">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-rose-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-rose-200 transition">
                    <i class="fa-solid fa-clipboard-list text-rose-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-primary-600 transition">Configuracao Portal de Inscricao</h3>
                    <p class="text-sm text-gray-500 mt-1">Formularios de inscricao, campos obrigatorios e fluxo de aprovacao.</p>
                    <span class="text-xs text-gray-400 mt-2 inline-block">Funcao 92</span>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
