<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BrasEduCRM') - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50:'#eff6ff',100:'#dbeafe',200:'#bfdbfe',300:'#93c5fd',400:'#60a5fa',500:'#3b82f6',600:'#2563eb',700:'#1d4ed8',800:'#1e40af',900:'#1e3a8a' },
                        sidebar: '#1e293b',
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-thin::-webkit-scrollbar { width: 6px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #475569; border-radius: 3px; }
        .submenu-item { font-size: 0.8rem; padding: 0.4rem 0.75rem 0.4rem 2.5rem; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen" x-data="{ sidebarOpen: true, searchOpen: false, activeModule: '{{ request()->segment(1, 'dashboard') }}' }">
    <div class="flex min-h-screen">

        {{-- SIDEBAR COM SUBMENUS EXPANDIVEIS --}}
        <aside class="fixed top-0 left-0 z-40 h-screen transition-all duration-300 bg-sidebar text-white flex flex-col"
               :class="sidebarOpen ? 'w-64' : 'w-16'">

            {{-- Logo --}}
            <div class="flex items-center justify-center h-14 border-b border-slate-700 px-3">
                <template x-if="sidebarOpen">
                    <span class="text-lg font-bold tracking-wide">BrasEdu<span class="text-primary-400">CRM</span></span>
                </template>
                <template x-if="!sidebarOpen">
                    <span class="text-lg font-bold text-primary-400">B</span>
                </template>
            </div>

            {{-- Search bar inside sidebar --}}
            <div class="px-3 py-2 border-b border-slate-700" x-show="sidebarOpen" x-cloak>
                <button @click="searchOpen = true" class="w-full flex items-center gap-2 px-3 py-1.5 bg-slate-700/50 rounded-lg text-slate-400 text-xs hover:bg-slate-700">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>Buscar...</span>
                    <span class="ml-auto text-[10px] border border-slate-600 rounded px-1">Ctrl+K</span>
                </button>
            </div>

            {{-- Menu items --}}
            <nav class="flex-1 overflow-y-auto scrollbar-thin py-1">

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                   class="flex items-center px-4 py-2.5 text-sm transition-colors hover:bg-slate-700 {{ request()->routeIs('dashboard') ? 'bg-slate-700 text-primary-400 border-r-2 border-primary-400' : 'text-slate-300' }}">
                    <i class="fa-solid fa-house w-5 text-center"></i>
                    <span class="ml-3" x-show="sidebarOpen" x-cloak>Dashboard</span>
                </a>

                {{-- ACADEMICO --}}
                <div x-data="{ open: activeModule === 'academico' }">
                    <button @click="open = !open" class="w-full flex items-center px-4 py-2.5 text-sm transition-colors hover:bg-slate-700 {{ request()->is('academico*') ? 'bg-slate-700 text-primary-400 border-r-2 border-primary-400' : 'text-slate-300' }}">
                        <i class="fa-solid fa-graduation-cap w-5 text-center"></i>
                        <span class="ml-3 flex-1 text-left" x-show="sidebarOpen" x-cloak>Academico</span>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="open && 'rotate-180'" x-show="sidebarOpen" x-cloak></i>
                    </button>
                    <div x-show="open && sidebarOpen" x-cloak x-collapse class="bg-slate-800/50">
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold">Cadastros Essenciais</p>
                        <a href="{{ Route::has('academico.cursos.index') ? route('academico.cursos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.cursos.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">25</span> Cadastro de Curso</a>
                        <a href="{{ Route::has('academico.disciplinas.index') ? route('academico.disciplinas.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.disciplinas.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">26</span> Cadastro de Disciplina</a>
                        <a href="{{ Route::has('academico.periodos-letivos.index') ? route('academico.periodos-letivos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">38</span> Cadastro de Periodo Letivo</a>
                        <a href="{{ Route::has('academico.salas.index') ? route('academico.salas.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">39</span> Cadastro de Sala</a>
                        <a href="{{ Route::has('academico.turnos.index') ? route('academico.turnos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">42</span> Cadastro de Turnos</a>
                        <a href="{{ route('cadastros.index', 'areas') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">24</span> Cadastro de Area</a>
                        <a href="{{ route('cadastros.index', 'graus') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">28</span> Cadastro de Grau</a>
                        <a href="{{ route('cadastros.index', 'habilitacoes') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">29</span> Cadastro de Habilitacao</a>
                        <a href="{{ route('cadastros.index', 'modulos') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">31</span> Cadastro de Modulos</a>
                        <a href="{{ Route::has('academico.configuracao.index') ? route('academico.configuracao.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.configuracao.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">167</span> Config. do Academico</a>
                        <a href="{{ Route::has('academico.configuracoes-boletim.index') ? route('academico.configuracoes-boletim.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.configuracoes-boletim.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">3</span> Config. do Boletim</a>
                        <a href="{{ Route::has('academico.tabelas-avaliacao.index') ? route('academico.tabelas-avaliacao.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.tabelas-avaliacao.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">5</span> Tabela de Avaliacao</a>

                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Matricula</p>
                        <a href="{{ Route::has('academico.matriculas.index') ? route('academico.matriculas.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.matriculas.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">23</span> Matricula e Historico</a>

                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Matriz Curricular</p>
                        <a href="{{ Route::has('academico.matrizes.index') ? route('academico.matrizes.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.matrizes.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">30</span> Cadastro de Matriz</a>
                        <a href="#" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">27</span> Emissao da Matriz</a>

                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Turmas</p>
                        <a href="{{ Route::has('academico.turmas.index') ? route('academico.turmas.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.turmas.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">40</span> Cadastro de Turma</a>
                        <a href="{{ Route::has('academico.montagem-turma.index') ? route('academico.montagem-turma.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.montagem-turma.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">41</span> Montagem de Turma</a>

                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Notas e Faltas</p>
                        <a href="{{ Route::has('academico.lancamento-notas.index') ? route('academico.lancamento-notas.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.lancamento-notas.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">1</span> Lancamento de Avaliacao</a>
                        <a href="{{ Route::has('academico.frequencia.index') ? route('academico.frequencia.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.frequencia.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">16</span> Frequencia</a>
                        <a href="{{ Route::has('academico.boletim.index') ? route('academico.boletim.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.boletim.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">2</span> Calculo do Boletim</a>
                        <a href="{{ Route::has('paineis.academico') ? route('paineis.academico') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('paineis.academico') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">144</span> Painel Academico</a>
                    </div>
                </div>

                {{-- ADMINISTRATIVO --}}
                <div x-data="{ open: activeModule === 'pessoas' || activeModule === 'alunos' || activeModule === 'requerimentos' || activeModule === 'atendimentos' || activeModule === 'documentos' }">
                    <button @click="open = !open" class="w-full flex items-center px-4 py-2.5 text-sm transition-colors hover:bg-slate-700 {{ request()->is('pessoas*') || request()->is('alunos*') || request()->is('requerimentos*') || request()->is('atendimentos*') || request()->is('documentos*') ? 'bg-slate-700 text-primary-400 border-r-2 border-primary-400' : 'text-slate-300' }}">
                        <i class="fa-solid fa-building w-5 text-center"></i>
                        <span class="ml-3 flex-1 text-left" x-show="sidebarOpen" x-cloak>Administrativo</span>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="open && 'rotate-180'" x-show="sidebarOpen" x-cloak></i>
                    </button>
                    <div x-show="open && sidebarOpen" x-cloak x-collapse class="bg-slate-800/50">
                        <a href="{{ route('pessoas.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('pessoas.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">11</span> Cadastro de Pessoa</a>
                        <a href="{{ route('alunos.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('alunos.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">17</span> Cadastro de Aluno</a>
                        <a href="{{ Route::has('requerimentos.index') ? route('requerimentos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">96</span> Requerimentos</a>
                        <a href="{{ Route::has('atendimentos.index') ? route('atendimentos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">55</span> Atendimentos</a>
                        <a href="{{ Route::has('documentos.index') ? route('documentos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">18</span> Documentos</a>
                        <a href="{{ Route::has('profissionais.index') ? route('profissionais.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('profissionais.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">12</span> Profissionais</a>
                        <a href="{{ Route::has('admin.operadores.index') ? route('admin.operadores.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('admin.operadores.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">44</span> Cadastro de Operador</a>
                        <a href="{{ Route::has('admin.grupos.index') ? route('admin.grupos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('admin.grupos.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">43</span> Grupo de Operadores</a>
                        <a href="{{ Route::has('admin.departamentos.index') ? route('admin.departamentos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('admin.departamentos.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">67</span> Departamentos</a>
                        <a href="{{ route('cadastros.index', 'instituicoes') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">7</span> Instituicao de Ensino</a>
                        <a href="{{ route('cadastros.index', 'escolas') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">8</span> Cadastro de Escola</a>
                        <a href="{{ route('cadastros.index', 'formas-ingresso') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">21</span> Forma de Ingresso</a>
                        <a href="{{ route('cadastros.index', 'religioes') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">13</span> Cadastro de Religiao</a>
                        <a href="{{ route('cadastros.index', 'profissoes') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">145</span> Cadastro de Profissoes</a>
                        <a href="{{ route('cadastros.index', 'tipos-profissional') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">14</span> Tipo de Profissional</a>
                        <a href="{{ route('cadastros.index', 'titularidades') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">15</span> Cadastro de Titularidade</a>
                        <a href="{{ route('cadastros.index', 'necessidades-especiais') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">10</span> Necessidades Especiais</a>
                        <a href="{{ route('cadastros.index', 'alergias') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">198</span> Cadastro de Alergia</a>
                    </div>
                </div>

                {{-- COMUNICACAO --}}
                <div x-data="{ open: activeModule === 'comunicacao' }">
                    <button @click="open = !open" class="w-full flex items-center px-4 py-2.5 text-sm transition-colors hover:bg-slate-700 {{ request()->is('comunicacao*') ? 'bg-slate-700 text-primary-400 border-r-2 border-primary-400' : 'text-slate-300' }}">
                        <i class="fa-solid fa-comments w-5 text-center"></i>
                        <span class="ml-3 flex-1 text-left" x-show="sidebarOpen" x-cloak>Comunicacao</span>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="open && 'rotate-180'" x-show="sidebarOpen" x-cloak></i>
                    </button>
                    <div x-show="open && sidebarOpen" x-cloak x-collapse class="bg-slate-800/50">
                        <a href="{{ route('comunicacao.templates.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('comunicacao.templates.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">87</span> Templates de Mensagens</a>
                        <a href="#" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">84</span> Mensagens Avulsas</a>
                        <a href="#" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">86</span> Aviso de Vencimento</a>
                        <a href="#" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">88</span> Aviso de Cobranca</a>
                        <a href="{{ Route::has('comunicacao.configuracao.index') ? route('comunicacao.configuracao.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('comunicacao.configuracao.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">85</span> Config. Comunicacao</a>
                    </div>
                </div>

                {{-- ESTOQUE --}}
                <div x-data="{ open: activeModule === 'estoque' }">
                    <button @click="open = !open" class="w-full flex items-center px-4 py-2.5 text-sm transition-colors hover:bg-slate-700 {{ request()->is('estoque*') ? 'bg-slate-700 text-primary-400 border-r-2 border-primary-400' : 'text-slate-300' }}">
                        <i class="fa-solid fa-boxes-stacked w-5 text-center"></i>
                        <span class="ml-3 flex-1 text-left" x-show="sidebarOpen" x-cloak>Estoque</span>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="open && 'rotate-180'" x-show="sidebarOpen" x-cloak></i>
                    </button>
                    <div x-show="open && sidebarOpen" x-cloak x-collapse class="bg-slate-800/50">
                        <a href="{{ route('estoque.produtos.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('estoque.produtos.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">148</span> Produtos de Estoque</a>
                        <a href="{{ Route::has('estoque.categorias.index') ? route('estoque.categorias.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">147</span> Categorias</a>
                        <a href="{{ Route::has('estoque.unidades.index') ? route('estoque.unidades.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">146</span> Unidades de Medida</a>
                        <a href="{{ Route::has('estoque.movimentacoes.index') ? route('estoque.movimentacoes.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">150</span> Movimentacoes</a>
                        <a href="{{ route('cadastros.index', 'depositos') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">153</span> Depositos</a>
                        <a href="#" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">154</span> Consulta de Estoque</a>
                    </div>
                </div>

                {{-- CRM --}}
                <div x-data="{ open: activeModule === 'crm' }">
                    <button @click="open = !open" class="w-full flex items-center px-4 py-2.5 text-sm transition-colors hover:bg-slate-700 {{ request()->is('crm*') ? 'bg-slate-700 text-primary-400 border-r-2 border-primary-400' : 'text-slate-300' }}">
                        <i class="fa-solid fa-handshake w-5 text-center"></i>
                        <span class="ml-3 flex-1 text-left" x-show="sidebarOpen" x-cloak>CRM</span>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="open && 'rotate-180'" x-show="sidebarOpen" x-cloak></i>
                    </button>
                    <div x-show="open && sidebarOpen" x-cloak x-collapse class="bg-slate-800/50">
                        <a href="{{ route('crm.funil.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('crm.funil.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">110</span> Funil de Oportunidades</a>
                        <a href="{{ route('crm.oportunidades.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('crm.oportunidades.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">109</span> Manut. Oportunidades</a>
                        <a href="{{ route('crm.interessados.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('crm.interessados.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">108</span> Interessados</a>
                        <a href="{{ route('crm.desempenho.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">190</span> Desempenho Consultor</a>
                        <a href="{{ Route::has('crm.origens.index') ? route('crm.origens.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">103</span> Origens</a>
                        <a href="{{ Route::has('crm.tags.index') ? route('crm.tags.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">171</span> Tags CRM</a>
                        <a href="{{ Route::has('crm.eventos.index') ? route('crm.eventos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">104</span> Eventos CRM</a>
                        <a href="{{ Route::has('crm.metas.index') ? route('crm.metas.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">191</span> Metas CRM</a>
                        <a href="{{ Route::has('crm.funil.create') ? route('crm.funil.create') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">200</span> Cadastro de Funil</a>
                        <a href="{{ Route::has('paineis.comercial') ? route('paineis.comercial') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('paineis.comercial') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">142</span> Painel Comercial</a>
                        <a href="{{ route('cadastros.index', 'categorias-interessado') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">207</span> Categorias Interessado</a>
                        <a href="{{ route('cadastros.index', 'produtos-servico') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">206</span> Produtos/Servicos CRM</a>
                        <a href="{{ route('cadastros.index', 'motivos-perda') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">107</span> Motivos de Perda</a>
                        <a href="{{ route('cadastros.index', 'motivos-ganho') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">212</span> Motivo de Ganho</a>
                        <a href="{{ route('cadastros.index', 'motivos-pausa') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">202</span> Motivo de Pausa</a>
                        <a href="{{ Route::has('crm.configuracoes.index') ? route('crm.configuracoes.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('crm.configuracoes.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">166</span> Config. do CRM</a>
                    </div>
                </div>

                {{-- EAD --}}
                <div x-data="{ open: activeModule === 'ead' }">
                    <button @click="open = !open" class="w-full flex items-center px-4 py-2.5 text-sm transition-colors hover:bg-slate-700 {{ request()->is('ead*') ? 'bg-slate-700 text-primary-400 border-r-2 border-primary-400' : 'text-slate-300' }}">
                        <i class="fa-solid fa-laptop w-5 text-center"></i>
                        <span class="ml-3 flex-1 text-left" x-show="sidebarOpen" x-cloak>EAD</span>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="open && 'rotate-180'" x-show="sidebarOpen" x-cloak></i>
                    </button>
                    <div x-show="open && sidebarOpen" x-cloak x-collapse class="bg-slate-800/50">
                        <a href="{{ route('ead.cursos.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('ead.cursos.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">152</span> Cadastro de Curso EAD</a>
                        <a href="#" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">214</span> Avaliacoes EAD</a>
                        <a href="#" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">156</span> Manut. Matriculas EAD</a>
                        <a href="#" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">188</span> Painel Academico EAD</a>
                    </div>
                </div>

                {{-- FINANCEIRO --}}
                <div x-data="{ open: activeModule === 'financeiro' }">
                    <button @click="open = !open" class="w-full flex items-center px-4 py-2.5 text-sm transition-colors hover:bg-slate-700 {{ request()->is('financeiro*') ? 'bg-slate-700 text-primary-400 border-r-2 border-primary-400' : 'text-slate-300' }}">
                        <i class="fa-solid fa-dollar-sign w-5 text-center"></i>
                        <span class="ml-3 flex-1 text-left" x-show="sidebarOpen" x-cloak>Financeiro</span>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="open && 'rotate-180'" x-show="sidebarOpen" x-cloak></i>
                    </button>
                    <div x-show="open && sidebarOpen" x-cloak x-collapse class="bg-slate-800/50">
                        <a href="{{ route('financeiro.titulos-receber.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('financeiro.titulos-receber.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">64</span> Titulos a Receber</a>
                        <a href="{{ route('financeiro.titulos-pagar.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('financeiro.titulos-pagar.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">52</span> Titulos a Pagar</a>
                        <a href="{{ route('financeiro.plano-contas.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('financeiro.plano-contas.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">50</span> Plano de Contas</a>
                        <a href="{{ route('financeiro.fluxo-caixa.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">78</span> Fluxo de Caixa</a>
                        <a href="{{ Route::has('financeiro.categorias-receber.index') ? route('financeiro.categorias-receber.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">65</span> Categorias a Receber</a>
                        <a href="{{ Route::has('financeiro.categorias-pagar.index') ? route('financeiro.categorias-pagar.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">51</span> Categorias a Pagar</a>
                        <a href="{{ Route::has('financeiro.contas-bancarias.index') ? route('financeiro.contas-bancarias.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">63</span> Contas Bancarias</a>
                        <a href="{{ Route::has('financeiro.descontos.index') ? route('financeiro.descontos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">57</span> Descontos</a>
                        <a href="{{ Route::has('financeiro.configuracao.index') ? route('financeiro.configuracao.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('financeiro.configuracao.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">59</span> Config. Financeiro</a>
                        <a href="{{ Route::has('financeiro.lancamentos.index') ? route('financeiro.lancamentos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('financeiro.lancamentos.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">61</span> Lancamentos Financeiros</a>
                        <a href="{{ Route::has('financeiro.caixas.index') ? route('financeiro.caixas.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('financeiro.caixas.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">68</span> Movim. de Caixas</a>
                        <a href="{{ Route::has('financeiro.renegociacoes.index') ? route('financeiro.renegociacoes.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('financeiro.renegociacoes.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">80</span> Renegociacoes</a>
                        <a href="{{ Route::has('financeiro.retorno.index') ? route('financeiro.retorno.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('financeiro.retorno.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">82</span> Importacao Retorno</a>
                        <a href="{{ Route::has('financeiro.dre.index') ? route('financeiro.dre.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('financeiro.dre.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">111</span> DRE</a>
                        <a href="{{ Route::has('paineis.financeiro') ? route('paineis.financeiro') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('paineis.financeiro') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">138</span> Painel Financeiro</a>
                    </div>
                </div>

                {{-- GED --}}
                <a href="{{ route('ged.index') }}" class="flex items-center px-4 py-2.5 text-sm transition-colors hover:bg-slate-700 {{ request()->is('ged*') ? 'bg-slate-700 text-primary-400 border-r-2 border-primary-400' : 'text-slate-300' }}">
                    <i class="fa-solid fa-folder-open w-5 text-center"></i>
                    <span class="ml-3" x-show="sidebarOpen" x-cloak>GED</span>
                </a>

                {{-- GERAL --}}
                <a href="{{ route('geral.index') }}" class="flex items-center px-4 py-2.5 text-sm transition-colors hover:bg-slate-700 {{ request()->is('geral*') ? 'bg-slate-700 text-primary-400 border-r-2 border-primary-400' : 'text-slate-300' }}">
                    <i class="fa-solid fa-table-cells w-5 text-center"></i>
                    <span class="ml-3" x-show="sidebarOpen" x-cloak>Geral</span>
                </a>

                {{-- INTEGRACOES --}}
                <a href="{{ route('integracoes.index') }}" class="flex items-center px-4 py-2.5 text-sm transition-colors hover:bg-slate-700 {{ request()->is('integracoes*') ? 'bg-slate-700 text-primary-400 border-r-2 border-primary-400' : 'text-slate-300' }}">
                    <i class="fa-solid fa-plug w-5 text-center"></i>
                    <span class="ml-3" x-show="sidebarOpen" x-cloak>Integracoes</span>
                </a>

                {{-- MATRICULA ONLINE --}}
                <a href="{{ route('matricula-online.index') }}" class="flex items-center px-4 py-2.5 text-sm transition-colors hover:bg-slate-700 {{ request()->is('matricula-online*') ? 'bg-slate-700 text-primary-400 border-r-2 border-primary-400' : 'text-slate-300' }}">
                    <i class="fa-solid fa-globe w-5 text-center"></i>
                    <span class="ml-3" x-show="sidebarOpen" x-cloak>Matr. Online</span>
                </a>

                {{-- PORTAIS --}}
                <a href="{{ route('portais.index') }}" class="flex items-center px-4 py-2.5 text-sm transition-colors hover:bg-slate-700 {{ request()->is('portais*') ? 'bg-slate-700 text-primary-400 border-r-2 border-primary-400' : 'text-slate-300' }}">
                    <i class="fa-solid fa-desktop w-5 text-center"></i>
                    <span class="ml-3" x-show="sidebarOpen" x-cloak>Portais</span>
                </a>

                {{-- PAINEL DO CLIENTE --}}
                <a href="{{ route('painel-cliente.index') }}" class="flex items-center px-4 py-2.5 text-sm transition-colors hover:bg-slate-700 {{ request()->is('painel-cliente*') ? 'bg-slate-700 text-primary-400 border-r-2 border-primary-400' : 'text-slate-300' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                    <span class="ml-3" x-show="sidebarOpen" x-cloak>Painel do Cliente</span>
                </a>
            </nav>

            {{-- Ajuda --}}
            <div class="border-t border-slate-700 p-2">
                <a href="{{ Route::has('tickets.index') ? route('tickets.index') : '#' }}" class="flex items-center px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 rounded">
                    <i class="fa-solid fa-circle-question w-5 text-center"></i>
                    <span class="ml-3" x-show="sidebarOpen" x-cloak>Ajuda</span>
                </a>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 transition-all duration-300" :class="sidebarOpen ? 'ml-64' : 'ml-16'">

            {{-- TOPBAR --}}
            <header class="sticky top-0 z-30 bg-white border-b border-gray-200 h-14 flex items-center justify-between px-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <nav class="flex items-center gap-1 text-sm">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-1 px-3 py-1.5 rounded-lg bg-primary-50 text-primary-600 font-medium">
                            <i class="fa-solid fa-house text-xs"></i> Inicio
                        </a>
                        @if(isset($breadcrumbs))
                            @foreach($breadcrumbs as $bc)
                            <span class="text-gray-400">/</span>
                            @if(isset($bc['url']))
                            <a href="{{ $bc['url'] }}" class="text-gray-600 hover:text-primary-600">{{ $bc['label'] }}</a>
                            @else
                            <span class="text-gray-800 font-medium">{{ $bc['label'] }}</span>
                            @endif
                            @endforeach
                        @endif
                    </nav>
                </div>

                <div class="flex items-center gap-1">
                    <button @click="searchOpen = !searchOpen" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg" title="Ctrl+K">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <a href="{{ route('dashboard') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fa-solid fa-house"></i>
                    </a>
                    <a href="{{ Route::has('notificacoes.index') ? route('notificacoes.index') : '#' }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg relative">
                        <i class="fa-solid fa-bell"></i>
                        @php $notifCount = \App\Models\Notificacao::where('user_id', Auth::id())->where('lida', false)->count() ?? 0; @endphp
                        @if($notifCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[10px] flex items-center justify-center rounded-full">{{ $notifCount }}</span>
                        @endif
                    </a>
                    <a href="{{ Route::has('calendario.index') ? route('calendario.index') : '#' }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fa-solid fa-calendar"></i>
                    </a>
                    <a href="{{ Route::has('tickets.index') ? route('tickets.index') : '#' }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fa-solid fa-message"></i>
                    </a>
                    <a href="{{ Route::has('tickets.create') ? route('tickets.create') : '#' }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fa-solid fa-circle-question"></i>
                    </a>
                    <a href="{{ Route::has('configuracoes.index') ? route('configuracoes.index') : '#' }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                        <i class="fa-solid fa-gear"></i>
                    </a>

                    {{-- User Menu --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 pl-3 pr-2 py-1.5 rounded-lg hover:bg-gray-100">
                            <div class="text-right hidden sm:block">
                                <div class="text-sm font-medium text-gray-700">{{ Auth::user()->nome }}</div>
                                <div class="text-xs text-gray-500">brasedu</div>
                            </div>
                            <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr(Auth::user()->nome, 0, 1)) }}
                            </div>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border py-1 z-50">
                            <a href="{{ Route::has('perfil.index') ? route('perfil.index') : '#' }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fa-solid fa-user mr-2"></i>Meu Perfil</a>
                            <a href="{{ Route::has('configuracoes.index') ? route('configuracoes.index') : '#' }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fa-solid fa-gear mr-2"></i>Configuracoes</a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fa-solid fa-right-from-bracket mr-2"></i>Sair
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- SEARCH MODAL --}}
            <div x-show="searchOpen" x-cloak @keydown.escape.window="searchOpen = false" @keydown.ctrl.k.window.prevent="searchOpen = !searchOpen"
                 class="fixed inset-0 z-50 flex items-start justify-center pt-20 bg-black/30">
                <div @click.away="searchOpen = false" class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-4">
                    <div class="flex items-center gap-3 border-b pb-3">
                        <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                        <input type="text" placeholder="Buscar funcao por ID ou nome..." class="flex-1 outline-none text-sm" autofocus
                               x-ref="searchInput" @keydown.escape="searchOpen = false">
                        <kbd class="px-2 py-0.5 text-xs bg-gray-100 rounded border">Ctrl+K</kbd>
                    </div>
                    <div class="mt-3 text-sm text-gray-500 text-center py-4">
                        Digite para buscar funcoes do sistema...
                    </div>
                </div>
            </div>

            {{-- FLASH MESSAGES --}}
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="mx-4 mt-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center justify-between text-sm">
                <span><i class="fa-solid fa-check-circle mr-2"></i>{{ session('success') }}</span>
                <button @click="show = false" class="text-green-500 hover:text-green-700"><i class="fa-solid fa-xmark"></i></button>
            </div>
            @endif

            @if(session('error'))
            <div x-data="{ show: true }" x-show="show"
                 class="mx-4 mt-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center justify-between text-sm">
                <span><i class="fa-solid fa-exclamation-circle mr-2"></i>{{ session('error') }}</span>
                <button @click="show = false" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-xmark"></i></button>
            </div>
            @endif

            {{-- PAGE CONTENT --}}
            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
