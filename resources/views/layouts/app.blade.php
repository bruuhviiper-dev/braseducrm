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
                        <a href="{{ Route::has('academico.calendarios.index') ? route('academico.calendarios.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.calendarios.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">35</span> Cadastro de Calendario</a>
                        <a href="{{ route('cadastros.index', 'escolas') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">8</span> Cadastro de Escola</a>
                        <a href="{{ Route::has('academico.grades-horario.index') ? route('academico.grades-horario.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.grades-horario.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">36</span> Cadastro de Grade de Horario</a>
                        <a href="{{ Route::has('academico.salas.index') ? route('academico.salas.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">39</span> Cadastro de Sala</a>
                        <a href="{{ Route::has('academico.turnos.index') ? route('academico.turnos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">42</span> Cadastro de Turnos</a>
                        <a href="{{ Route::has('academico.configuracao.index') ? route('academico.configuracao.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.configuracao.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">167</span> Configuracao do Academico</a>
                        <a href="{{ Route::has('academico.configuracoes-boletim.index') ? route('academico.configuracoes-boletim.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.configuracoes-boletim.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">3</span> Configuracao do Boletim</a>
                        <a href="{{ Route::has('academico.programacoes-avaliacao.index') ? route('academico.programacoes-avaliacao.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.programacoes-avaliacao.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">4</span> Programacoes de Avaliacoes</a>
                        <a href="{{ Route::has('academico.frequencia.index') ? route('academico.frequencia.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.frequencia.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">268</span> Registro de Frequencia</a>
                        <a href="{{ Route::has('academico.tabelas-avaliacao.index') ? route('academico.tabelas-avaliacao.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.tabelas-avaliacao.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">5</span> Tabela de Avaliacao</a>

                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Diploma Digital</p>
                        <a href="{{ Route::has('ged.diplomas.index') ? route('ged.diplomas.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">215</span> Cadastro de Diploma Digital</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">226</span> Historico Escolar Digital</a>

                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Documentos</p>
                        <a href="{{ Route::has('documentos.index') ? route('documentos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">18</span> Cadastro de Documento</a>
                        <a href="{{ Route::has('academico.entregas-documento.consulta') ? route('academico.entregas-documento.consulta') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.entregas-documento.consulta') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">102</span> Consulta Docs nao Entregues</a>
                        <a href="{{ Route::has('academico.emissoes.documentos') ? route('academico.emissoes.documentos') : '#' }}" target="_blank" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">210</span> Emissao de Documentos</a>
                        <a href="{{ Route::has('academico.entregas-documento.index') ? route('academico.entregas-documento.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.entregas-documento.index') || request()->routeIs('academico.entregas-documento.gerenciar') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">19</span> Entrega de Documentos</a>

                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Matricula</p>
                        <a href="{{ route('alunos.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('alunos.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">17</span> Cadastro de Aluno</a>
                        <a href="{{ Route::has('academico.matriculas.index') ? route('academico.matriculas.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.matriculas.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">23</span> Matricula e Historico</a>
                        <a href="{{ route('cadastros.index', 'formas-ingresso') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">21</span> Forma de Ingresso</a>
                        <a href="{{ Route::has('academico.horas-complementares.index') ? route('academico.horas-complementares.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.horas-complementares.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">239</span> Controle de Horas Complementares</a>
                        <a href="{{ Route::has('academico.praticas-supervisionadas.index') ? route('academico.praticas-supervisionadas.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.praticas-supervisionadas.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">90</span> Controle de Pratica Supervisionada</a>
                        <a href="{{ Route::has('academico.rematriculas.index') ? route('academico.rematriculas.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.rematriculas.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">279</span> Controle de Rematriculas</a>
                        <a href="{{ Route::has('academico.exames-nivel.index') ? route('academico.exames-nivel.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.exames-nivel.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">183</span> Manutencao de Exame de Nivel</a>
                        <a href="{{ Route::has('academico.emissoes.index') ? route('academico.emissoes.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.emissoes.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">79</span> Emissao de Alunos Matriculados</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">98</span> Emissao do Historico Escolar</a>
                        <a href="{{ route('cadastros.index', 'tags-matricula') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">169</span> Cadastro de Tag de Matricula</a>
                        <a href="{{ route('cadastros.index', 'motivos-cancelamento') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">242</span> Motivo de Cancelamento Matricula</a>
                        <a href="{{ Route::has('academico.emissoes.index') ? route('academico.emissoes.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">305</span> Emissao de Disciplinas dos Alunos</a>

                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Matriz Curricular</p>
                        <a href="{{ route('cadastros.index', 'areas') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">24</span> Cadastro de Area</a>
                        <a href="{{ Route::has('ged.atos.index') ? route('ged.atos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">216</span> Cadastro de Atos Regulatorios</a>
                        <a href="{{ route('cadastros.index', 'conceitos-nota') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">176</span> Cadastro de Conceito de Notas</a>
                        <a href="{{ Route::has('academico.cursos.index') ? route('academico.cursos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.cursos.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">25</span> Cadastro de Curso</a>
                        <a href="{{ Route::has('academico.disciplinas.index') ? route('academico.disciplinas.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.disciplinas.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">26</span> Cadastro de Disciplina</a>
                        <a href="{{ route('cadastros.index', 'graus') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">28</span> Cadastro de Grau</a>
                        <a href="{{ route('cadastros.index', 'habilitacoes') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">29</span> Cadastro de Habilitacao</a>
                        <a href="{{ Route::has('academico.matrizes.index') ? route('academico.matrizes.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.matrizes.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">30</span> Cadastro de Matriz Curricular</a>
                        <a href="{{ route('cadastros.index', 'modulos') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">31</span> Cadastro de Modulos</a>
                        <a href="{{ Route::has('academico.emissoes.index') ? route('academico.emissoes.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">27</span> Emissao da Matriz Curricular</a>

                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Notas e Faltas</p>
                        <a href="{{ Route::has('academico.boletim.index') ? route('academico.boletim.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.boletim.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">2</span> Calculo do Boletim</a>
                        <a href="{{ Route::has('academico.emissoes.index') ? route('academico.emissoes.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">60</span> Emissao de Notas e Faltas</a>
                        <a href="{{ Route::has('academico.emissoes.index') ? route('academico.emissoes.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">91</span> Emissao do Diario de Classe</a>
                        <a href="{{ Route::has('academico.emissoes.index') ? route('academico.emissoes.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">249</span> Emissao de Pendencias Notas e Faltas</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">137</span> Exclusao de Notas e Faltas</a>
                        <a href="{{ Route::has('academico.frequencia.index') ? route('academico.frequencia.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">16</span> Frequencia e Conteudo Ministrado</a>
                        <a href="{{ Route::has('academico.lancamento-notas.index') ? route('academico.lancamento-notas.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.lancamento-notas.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">1</span> Lancamento de Avaliacao</a>
                        <a href="{{ Route::has('academico.liberacoes-frequencia.index') ? route('academico.liberacoes-frequencia.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.liberacoes-frequencia.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">262</span> Liberar Lancamento de Frequencia</a>

                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Plano de Ensino/Aula</p>
                        <a href="{{ Route::has('academico.estruturas-plano.index') ? route('academico.estruturas-plano.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.estruturas-plano.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">204</span> Cadastro de Estrutura do Plano</a>
                        <a href="{{ route('cadastros.index', 'topicos-plano') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">203</span> Cadastro de Topico do Plano</a>
                        <a href="{{ Route::has('academico.planos-ensino.index') ? route('academico.planos-ensino.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.planos-ensino.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">119</span> Preenchimento Plano de Ensino</a>

                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Requerimentos</p>
                        <a href="{{ Route::has('requerimentos.index') ? route('requerimentos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">96</span> Manutencao de Requerimentos</a>

                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Turmas</p>
                        <a href="{{ Route::has('academico.periodos-letivos.index') ? route('academico.periodos-letivos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">38</span> Cadastro de Periodo Letivo</a>
                        <a href="{{ Route::has('academico.turmas.index') ? route('academico.turmas.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.turmas.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">40</span> Cadastro de Turma</a>
                        <a href="{{ route('cadastros.index', 'tags-turma-montada') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">251</span> Cadastro de Tag (Turma Montada)</a>
                        <a href="{{ Route::has('academico.montagem-turma.index') ? route('academico.montagem-turma.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.montagem-turma.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">41</span> Montagem de Turma e Horario</a>
                        <a href="{{ Route::has('academico.emissoes.index') ? route('academico.emissoes.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">184</span> Emissao de Turmas Montadas</a>
                        <a href="{{ Route::has('academico.emissoes.index') ? route('academico.emissoes.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">185</span> Emissao de Horarios Professores</a>
                        <a href="{{ Route::has('academico.painel-professor.index') ? route('academico.painel-professor.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.painel-professor.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">257</span> Painel do Professor</a>
                        <a href="{{ Route::has('academico.planejamento-diario.index') ? route('academico.planejamento-diario.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('academico.planejamento-diario.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">45</span> Planejamento Diario de Aulas</a>
                        <a href="{{ Route::has('academico.emissoes.index') ? route('academico.emissoes.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">114</span> Declaracao de Aula Ministrada</a>
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
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold">Acessos</p>
                        <a href="{{ Route::has('admin.grupos.index') ? route('admin.grupos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('admin.grupos.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">43</span> Cadastro de Grupo de Operadores</a>
                        <a href="{{ Route::has('admin.operadores.index') ? route('admin.operadores.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('admin.operadores.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">44</span> Cadastro de Operador</a>
                        <a href="{{ route('painel-cliente.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('painel-cliente.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">112</span> Painel do Cliente</a>
                    </div>
                </div>

                {{-- BIBLIOTECA --}}
                <div x-data="{ open: activeModule === 'biblioteca' }">
                    <button @click="open = !open" class="w-full flex items-center px-4 py-2.5 text-sm transition-colors hover:bg-slate-700 {{ request()->is('biblioteca*') ? 'bg-slate-700 text-primary-400 border-r-2 border-primary-400' : 'text-slate-300' }}">
                        <i class="fa-solid fa-book w-5 text-center"></i>
                        <span class="ml-3 flex-1 text-left" x-show="sidebarOpen" x-cloak>Biblioteca</span>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="open && 'rotate-180'" x-show="sidebarOpen" x-cloak></i>
                    </button>
                    <div x-show="open && sidebarOpen" x-cloak x-collapse class="bg-slate-800/50">
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold">Acervo</p>
                        <a href="{{ Route::has('biblioteca.obras.index') ? route('biblioteca.obras.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('biblioteca.obras.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">288</span> Cadastro de Obra</a>
                        <a href="{{ Route::has('biblioteca.exemplares.index') ? route('biblioteca.exemplares.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('biblioteca.exemplares.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">286</span> Cadastro de Exemplares</a>
                        <a href="{{ Route::has('biblioteca.movimentacoes.index') ? route('biblioteca.movimentacoes.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('biblioteca.movimentacoes.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">287</span> Movimentacoes de Exemplares</a>
                        <a href="{{ Route::has('biblioteca.reservas.index') ? route('biblioteca.reservas.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('biblioteca.reservas.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">289</span> Reserva de Exemplares</a>
                        <a href="{{ Route::has('biblioteca.emissoes.etiquetas') ? route('biblioteca.emissoes.etiquetas') : '#' }}" target="_blank" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">283</span> Emissao de Etiquetas</a>
                        <a href="{{ Route::has('biblioteca.emissoes.exemplares') ? route('biblioteca.emissoes.exemplares') : '#' }}" target="_blank" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">284</span> Emissao de Exemplares</a>
                        <a href="{{ Route::has('biblioteca.emissoes.movimentacoes') ? route('biblioteca.emissoes.movimentacoes') : '#' }}" target="_blank" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">285</span> Emissao de Movimentacoes</a>
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Cadastros Essenciais</p>
                        <a href="{{ route('cadastros.index', 'autores') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">290</span> Cadastro de Autores</a>
                        <a href="{{ route('cadastros.index', 'bibliotecas') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">291</span> Cadastro de Biblioteca</a>
                        <a href="{{ route('cadastros.index', 'colecoes') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">292</span> Cadastro de Colecao</a>
                        <a href="{{ route('cadastros.index', 'editores') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">293</span> Cadastro de Editores</a>
                        <a href="{{ route('cadastros.index', 'estados-conservacao') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">294</span> Estado de Conservacao</a>
                        <a href="{{ route('cadastros.index', 'idiomas') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">295</span> Cadastro de Idiomas</a>
                        <a href="{{ route('cadastros.index', 'tipos-aquisicao') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">297</span> Tipo de Aquisicao</a>
                        <a href="{{ route('cadastros.index', 'tipos-material') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">298</span> Tipo de Material</a>
                        <a href="{{ route('cadastros.index', 'motivos-indisponibilidade') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">296</span> Motivo de Indisponibilidade</a>
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Configuracao</p>
                        <a href="{{ Route::has('biblioteca.configuracao.index') ? route('biblioteca.configuracao.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('biblioteca.configuracao.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">299</span> Configuracao do Biblioteca</a>
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
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold">Mensagens</p>
                        <a href="{{ Route::has('comunicacao.mensagens.avulsa') ? route('comunicacao.mensagens.avulsa') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('comunicacao.mensagens.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">84</span> Mensagens Avulsas</a>
                        <a href="{{ Route::has('comunicacao.mensagens.avisos') ? route('comunicacao.mensagens.avisos') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">88</span> Aviso de Cobranca</a>
                        <a href="{{ Route::has('comunicacao.mensagens.avisos') ? route('comunicacao.mensagens.avisos') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">86</span> Aviso de Vencimento</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">234</span> Aviso de Pagamento</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">62</span> Mensagens para Interessados CRM</a>
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Configuracao</p>
                        <a href="{{ route('comunicacao.templates.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('comunicacao.templates.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">87</span> Templates de Mensagens</a>
                        <a href="{{ Route::has('comunicacao.configuracao.index') ? route('comunicacao.configuracao.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('comunicacao.configuracao.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">85</span> Configuracao da Comunicacao</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">89</span> Consulta de Saldo SMS</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">247</span> Numeros Whatsapp</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">260</span> Central de Notificacao do Aluno</a>
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
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold">Cadastros Essenciais</p>
                        <a href="{{ Route::has('estoque.categorias.index') ? route('estoque.categorias.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">147</span> Cadastro de Categorias de Estoque</a>
                        <a href="{{ route('cadastros.index', 'depositos') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">153</span> Cadastro de Depositos de Estoque</a>
                        <a href="{{ route('estoque.produtos.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('estoque.produtos.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">148</span> Cadastro de Produtos de Estoque</a>
                        <a href="{{ Route::has('estoque.unidades.index') ? route('estoque.unidades.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">146</span> Cadastro de Unidades de Medida</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">186</span> Emissao de Produtos de Estoques</a>
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Movimentacoes</p>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">154</span> Consulta de Estoque</a>
                        <a href="{{ Route::has('estoque.movimentacoes.index') ? route('estoque.movimentacoes.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">150</span> Movimentacoes de Estoque</a>
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
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold">Cadastros Essenciais</p>
                        <a href="{{ route('cadastros.index', 'categorias-interessado') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">207</span> Categorias (Interessados)</a>
                        <a href="{{ Route::has('crm.eventos.index') ? route('crm.eventos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">104</span> Eventos CRM</a>
                        <a href="{{ Route::has('crm.funil.index') ? route('crm.funil.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('crm.funil.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">200</span> Cadastro de Funil de Oportunidades</a>
                        <a href="{{ Route::has('crm.metas.index') ? route('crm.metas.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">191</span> Metas CRM</a>
                        <a href="{{ route('cadastros.index', 'motivos-ganho') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">212</span> Motivo de Ganho</a>
                        <a href="{{ route('cadastros.index', 'motivos-pausa') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">202</span> Motivo de Pausa</a>
                        <a href="{{ route('cadastros.index', 'motivos-perda') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">107</span> Motivos de Perda</a>
                        <a href="{{ Route::has('crm.origens.index') ? route('crm.origens.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">103</span> Origem do Interessado</a>
                        <a href="{{ route('cadastros.index', 'produtos-servico') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">206</span> Produtos/Servicos CRM</a>
                        <a href="{{ Route::has('crm.tags.index') ? route('crm.tags.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">171</span> Tag CRM</a>
                        <a href="{{ Route::has('crm.configuracoes.index') ? route('crm.configuracoes.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('crm.configuracoes.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">166</span> Configuracao do CRM</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">256</span> Acao Automatica (CRM)</a>

                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Oportunidades</p>
                        <a href="{{ route('crm.interessados.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('crm.interessados.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">108</span> Cadastro de Interessados</a>
                        <a href="{{ route('crm.desempenho.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">190</span> Desempenho Individual do Consultor</a>
                        <a href="{{ route('crm.funil.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('crm.funil.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">110</span> Funil de Oportunidades</a>
                        <a href="{{ route('crm.oportunidades.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('crm.oportunidades.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">109</span> Manutencao de Oportunidades</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">201</span> Emissao de Propostas (CRM)</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">159</span> Exportacao de Oportunidades</a>
                        <a href="{{ Route::has('paineis.comercial') ? route('paineis.comercial') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('paineis.comercial') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">142</span> Painel Comercial</a>
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
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold">Cadastros Essenciais</p>
                        <a href="{{ route('ead.cursos.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('ead.cursos.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">152</span> Cadastro de Curso (EAD)</a>
                        <a href="{{ route('cadastros.index', 'agrupadores-curso') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">211</span> Cadastro de Agrupador de Cursos</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">266</span> Cadastro de Sub Agrupador (EAD)</a>
                        <a href="{{ route('cadastros.index', 'tags-curso-ead') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">246</span> Cadastro de Tag (Curso EAD)</a>
                        <a href="{{ Route::has('ead.avaliacoes.index') ? route('ead.avaliacoes.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('ead.avaliacoes.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">214</span> Cadastro de Avaliacoes EAD</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">238</span> Cadastro de Questoes Avulsas</a>
                        <a href="{{ route('cadastros.index', 'tags-questao') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">236</span> Cadastro de Tag de Questoes</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">241</span> Gerador de Avaliacoes</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">301</span> Cadastro de Videos</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">306</span> Foruns EAD</a>
                        <a href="{{ Route::has('ead.matriculas.index') ? route('ead.matriculas.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('ead.matriculas.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">156</span> Manutencao de Matriculas EAD</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">174</span> Emissao de Alunos Matriculados EAD</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">219</span> Emissao de notas alunos (EAD)</a>
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
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold">Cadastros Essenciais</p>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">47</span> Cadastro de Banco</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">274</span> Cadastro de Centro de Custos</a>
                        <a href="{{ Route::has('financeiro.categorias-pagar.index') ? route('financeiro.categorias-pagar.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">51</span> Categorias (A Pagar)</a>
                        <a href="{{ Route::has('financeiro.categorias-receber.index') ? route('financeiro.categorias-receber.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">65</span> Categorias (A Receber)</a>
                        <a href="{{ Route::has('financeiro.contas-bancarias.index') ? route('financeiro.contas-bancarias.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">63</span> Cadastro de Contas</a>
                        <a href="{{ Route::has('financeiro.descontos.index') ? route('financeiro.descontos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">58</span> Desconto Condicional</a>
                        <a href="{{ Route::has('financeiro.descontos.index') ? route('financeiro.descontos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">57</span> Desconto Incondicional</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">53</span> Forma de Pagamento</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">213</span> Taxas de Cartao Avulso</a>
                        <a href="{{ route('financeiro.plano-contas.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('financeiro.plano-contas.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">50</span> Cadastro do Plano de Contas</a>
                        <a href="{{ Route::has('financeiro.configuracao.index') ? route('financeiro.configuracao.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('financeiro.configuracao.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">59</span> Configuracao do Financeiro</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">227</span> Configuracoes de NFS-e</a>

                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Caixa (Movimentacoes)</p>
                        <a href="{{ Route::has('financeiro.caixas.index') ? route('financeiro.caixas.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('financeiro.caixas.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">68</span> Movimentacoes de Caixas</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">106</span> Emissao do Fechamento de Caixa</a>

                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Cartoes</p>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">70</span> Contratos de Cartoes</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">71</span> Conciliacao de Recebimentos (Cartao)</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">136</span> Cartao de Credito Empresarial</a>

                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Cheques</p>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">72</span> Manutencao de Cheques</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">73</span> Motivo de Devolucao (Cheque)</a>

                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Lancamentos Financeiros</p>
                        <a href="{{ Route::has('financeiro.dre.index') ? route('financeiro.dre.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('financeiro.dre.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">111</span> Demonstrativo de Resultados (DRE)</a>
                        <a href="{{ route('financeiro.fluxo-caixa.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">78</span> Fluxo de Caixa (Mensal)</a>
                        <a href="{{ Route::has('financeiro.lancamentos.index') ? route('financeiro.lancamentos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('financeiro.lancamentos.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">61</span> Lancamentos Financeiros</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">195</span> Fluxo de Caixa (Diario)</a>

                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Titulos a pagar</p>
                        <a href="{{ route('financeiro.titulos-pagar.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('financeiro.titulos-pagar.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">52</span> Manutencao de Titulos a Pagar</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">173</span> Emissao de Titulos a Pagar</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">180</span> Emissao de Comissoes</a>

                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Titulos a receber</p>
                        <a href="{{ route('financeiro.titulos-receber.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('financeiro.titulos-receber.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">64</span> Manutencao de Titulos a Receber</a>
                        <a href="{{ Route::has('financeiro.renegociacoes.index') ? route('financeiro.renegociacoes.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('financeiro.renegociacoes.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">80</span> Renegociacoes de Parcelas</a>
                        <a href="{{ Route::has('financeiro.retorno.index') ? route('financeiro.retorno.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('financeiro.retorno.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">82</span> Importacao do Arquivo de Retorno</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">66</span> Emissao de Boletos Bancarios</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">113</span> Emissao de Cobranca</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">93</span> Conta Corrente Por Pessoa</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">101</span> Resumo Financeiro da Pessoa</a>
                        <a href="{{ Route::has('paineis.financeiro') ? route('paineis.financeiro') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('paineis.financeiro') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">138</span> Painel Financeiro</a>
                    </div>
                </div>

                {{-- GED --}}
                <div x-data="{ open: activeModule === 'ged' }">
                    <button @click="open = !open" class="w-full flex items-center px-4 py-2.5 text-sm transition-colors hover:bg-slate-700 {{ request()->is('ged*') ? 'bg-slate-700 text-primary-400 border-r-2 border-primary-400' : 'text-slate-300' }}">
                        <i class="fa-solid fa-folder-open w-5 text-center"></i>
                        <span class="ml-3 flex-1 text-left" x-show="sidebarOpen" x-cloak>GED</span>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="open && 'rotate-180'" x-show="sidebarOpen" x-cloak></i>
                    </button>
                    <div x-show="open && sidebarOpen" x-cloak x-collapse class="bg-slate-800/50">
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold">Cadastros Essenciais</p>
                        <a href="{{ Route::has('ged.documentos.index') ? route('ged.documentos.index') : route('ged.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->is('ged*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">244</span> Documento (GED)</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">245</span> Categoria do Documento (GED)</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">252</span> Tipo de Documento</a>
                    </div>
                </div>

                {{-- GERAL --}}
                <div x-data="{ open: activeModule === 'geral' || activeModule === 'pessoas' || activeModule === 'atendimentos' }">
                    <button @click="open = !open" class="w-full flex items-center px-4 py-2.5 text-sm transition-colors hover:bg-slate-700 {{ request()->is('geral*') || request()->is('pessoas*') || request()->is('profissionais*') || request()->is('atendimentos*') ? 'bg-slate-700 text-primary-400 border-r-2 border-primary-400' : 'text-slate-300' }}">
                        <i class="fa-solid fa-table-cells w-5 text-center"></i>
                        <span class="ml-3 flex-1 text-left" x-show="sidebarOpen" x-cloak>Geral</span>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="open && 'rotate-180'" x-show="sidebarOpen" x-cloak></i>
                    </button>
                    <div x-show="open && sidebarOpen" x-cloak x-collapse class="bg-slate-800/50">
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold">Atendimentos</p>
                        <a href="{{ Route::has('atendimentos.index') ? route('atendimentos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">55</span> Manutencao de Atendimentos</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">172</span> Atendimentos Pool (Follow up)</a>
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Cadastros Essenciais</p>
                        <a href="{{ Route::has('admin.departamentos.index') ? route('admin.departamentos.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">67</span> Cadastro de Departamento</a>
                        <a href="{{ route('cadastros.index', 'instituicoes') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">7</span> Cadastro de Instituicao de Ensino</a>
                        <a href="{{ Route::has('geral.questionarios.index') ? route('geral.questionarios.index') : route('geral.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">118</span> Cadastro de Questionarios NPS</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">97</span> Cadastro de Atributos Adicionais</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">221</span> Consulta Personalizada</a>
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Configuracoes de Emissoes</p>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">6</span> Cadastro de Assinatura</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">9</span> Cadastro de Modelo de Documentos</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">48</span> Modelos de Cabecalho</a>
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Indicacao</p>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">225</span> Campanha de Indicacao</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">223</span> Controle de Indicacoes</a>
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Pessoas</p>
                        <a href="{{ route('pessoas.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('pessoas.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">11</span> Cadastro de Pessoa</a>
                        <a href="{{ Route::has('profissionais.index') ? route('profissionais.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700 {{ request()->routeIs('profissionais.*') ? 'text-primary-400' : '' }}"><span class="text-slate-600 mr-1">12</span> Cadastro de Profissional</a>
                        <a href="{{ route('cadastros.index', 'profissoes') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">145</span> Cadastro de Profissoes</a>
                        <a href="{{ route('cadastros.index', 'religioes') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">13</span> Cadastro de Religiao</a>
                        <a href="{{ route('cadastros.index', 'tipos-profissional') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">14</span> Tipo de Profissional</a>
                        <a href="{{ route('cadastros.index', 'titularidades') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">15</span> Cadastro de Titularidade</a>
                        <a href="{{ route('cadastros.index', 'necessidades-especiais') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">10</span> Necessidades Especiais</a>
                        <a href="{{ route('cadastros.index', 'alergias') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">198</span> Cadastro de Alergia</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">164</span> Aniversariantes</a>
                    </div>
                </div>

                {{-- INTEGRACOES --}}
                <a href="{{ route('integracoes.index') }}" class="flex items-center px-4 py-2.5 text-sm transition-colors hover:bg-slate-700 {{ request()->is('integracoes*') ? 'bg-slate-700 text-primary-400 border-r-2 border-primary-400' : 'text-slate-300' }}">
                    <i class="fa-solid fa-plug w-5 text-center"></i>
                    <span class="ml-3" x-show="sidebarOpen" x-cloak>Integracoes</span>
                </a>

                {{-- MATRICULA ONLINE --}}
                <div x-data="{ open: activeModule === 'matricula-online' }">
                    <button @click="open = !open" class="w-full flex items-center px-4 py-2.5 text-sm transition-colors hover:bg-slate-700 {{ request()->is('matricula-online*') ? 'bg-slate-700 text-primary-400 border-r-2 border-primary-400' : 'text-slate-300' }}">
                        <i class="fa-solid fa-globe w-5 text-center"></i>
                        <span class="ml-3 flex-1 text-left" x-show="sidebarOpen" x-cloak>Matr. Online</span>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="open && 'rotate-180'" x-show="sidebarOpen" x-cloak></i>
                    </button>
                    <div x-show="open && sidebarOpen" x-cloak x-collapse class="bg-slate-800/50">
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold">Processos</p>
                        <a href="{{ Route::has('matricula-online.aberturas.index') ? route('matricula-online.aberturas.index') : route('matricula-online.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">140</span> Abertura de Matricula Online</a>
                        <a href="{{ Route::has('matricula-online.inscricoes.index') ? route('matricula-online.inscricoes.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">149</span> Acompanhamento de Inscricoes</a>
                        <a href="{{ Route::has('matricula-online.cupons.index') ? route('matricula-online.cupons.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">182</span> Cadastro de Cupons de Desconto</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">193</span> Cadastro de Cupons Personalizados</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">74</span> Cadastro de Tag Matricula Online</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">187</span> Emissao de Inscricoes</a>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">151</span> Painel de Inscricoes Online</a>
                    </div>
                </div>

                {{-- PORTAIS --}}
                <div x-data="{ open: activeModule === 'portais' }">
                    <button @click="open = !open" class="w-full flex items-center px-4 py-2.5 text-sm transition-colors hover:bg-slate-700 {{ request()->is('portais*') ? 'bg-slate-700 text-primary-400 border-r-2 border-primary-400' : 'text-slate-300' }}">
                        <i class="fa-solid fa-desktop w-5 text-center"></i>
                        <span class="ml-3 flex-1 text-left" x-show="sidebarOpen" x-cloak>Portais</span>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="open && 'rotate-180'" x-show="sidebarOpen" x-cloak></i>
                    </button>
                    <div x-show="open && sidebarOpen" x-cloak x-collapse class="bg-slate-800/50">
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold">Configuracao</p>
                        <a href="{{ Route::has('portais.configuracao') ? route('portais.configuracao') : route('portais.index') }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">46</span> Configuracao Portal Aluno</a>
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Publicacao (Portal do Aluno)</p>
                        <a href="{{ Route::has('portais.pastas.index') ? route('portais.pastas.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">76</span> Cadastro de Pastas Portal Aluno</a>
                        <a href="{{ Route::has('portais.publicacoes.index') ? route('portais.publicacoes.index') : '#' }}" class="submenu-item block text-slate-400 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">77</span> Publicacoes Portal Aluno</a>
                        <p class="px-4 py-1.5 text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">Feedbacks</p>
                        <a href="#" class="submenu-item block text-slate-500 hover:text-white hover:bg-slate-700"><span class="text-slate-600 mr-1">69</span> Resultados Feedback Professores</a>
                    </div>
                </div>
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
