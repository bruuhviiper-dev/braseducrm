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
            darkMode: 'class',
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
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .submenu-item { font-size: 0.8rem; padding: 0.4rem 0.75rem 0.4rem 2.5rem; }
    </style>
    @stack('styles')
    <style>
        .fl-wrap{position:relative}
        .fl-wrap>.fl-label{position:absolute;left:12px;top:50%;transform:translateY(-50%);pointer-events:none;color:#94a3b8;font-size:13px;font-weight:400;transition:all .12s ease;white-space:nowrap;max-width:calc(100% - 24px);overflow:hidden;text-overflow:ellipsis;margin:0;z-index:1}
        .fl-wrap.fl-area>.fl-label{top:18px;transform:none}
        .fl-wrap.fl-float>.fl-label{top:0;transform:translateY(-50%);font-size:11px;color:#0891b2;background:#fff;padding:0 4px;max-width:none;font-weight:500}
        .dark .fl-wrap.fl-float>.fl-label{background:#1f2937;color:#22d3ee}
        .fl-wrap>input,.fl-wrap>select,.fl-wrap>textarea{padding-top:.7rem!important;padding-bottom:.7rem!important;border-radius:.5rem!important}
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen" x-data="{ searchOpen: false, openMod: null, dark: localStorage.getItem('braseducrm_dark')==='true' }" x-init="document.documentElement.classList.toggle('dark', dark); $watch('dark', v => document.documentElement.classList.toggle('dark', v))">
    <div class="flex min-h-screen">

        {{-- SIDEBAR COM SUBMENUS EXPANDIVEIS --}}
        <aside class="fixed top-0 left-0 z-40 h-screen bg-[#0d0f12] text-gray-300 flex flex-col w-28">

            {{-- Logo (wordmark branca em bloco escuro, estilo EDUQ) --}}
            <div class="flex items-center justify-center h-14 border-b border-white/10 px-2 bg-black/30">
                <span class="text-xl font-extrabold text-white tracking-tight lowercase">brasedu</span>
            </div>

            {{-- Search bar inside sidebar --}}
            <div class="px-2 py-2 border-b border-white/10"><button @click="searchOpen = true" class="w-full flex flex-col items-center gap-1 py-1.5 text-[10px] text-gray-400 hover:text-white hover:bg-white/10 rounded-lg" title="Buscar (Ctrl+K)"><i class="fa-solid fa-magnifying-glass text-base"></i><span>Ctrl K</span></button></div>

            {{-- Menu items --}}
            <nav class="flex-1 overflow-y-auto scrollbar-thin py-1">

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}" class="w-[calc(100%-16px)] mx-2 my-0.5 rounded-xl flex flex-col items-center justify-center gap-1.5 py-2.5 text-[10px] leading-tight transition-colors hover:bg-white/10 {{ request()->routeIs('dashboard') ? 'bg-gray-200 text-gray-900' : 'text-gray-400' }}"><i class="fa-solid fa-house text-lg"></i><span class="text-center px-0.5">Dashboard</span></a>

                {{-- ACADEMICO --}}
                <div>
                    <button @click="openMod = openMod===0 ? null : 0" class="w-[calc(100%-16px)] mx-2 my-0.5 rounded-xl flex flex-col items-center justify-center gap-1.5 py-2.5 text-[10px] leading-tight transition-colors hover:bg-white/10 {{ request()->is('academico*') ? 'bg-gray-200 text-gray-900' : 'text-gray-400' }}" :class="openMod===0 ? 'bg-gray-200 text-gray-900' : ''"><i class="fa-solid fa-graduation-cap text-lg"></i><span class="text-center px-0.5">Acadêmico</span></button>
                    <div x-show="openMod===0" x-cloak class="fixed left-28 top-0 z-50 w-80 h-screen bg-[#2a2f36] shadow-2xl border border-black/30 overflow-y-auto py-2"><div class="px-3 pb-2 pt-1 space-y-2"><input type="text" placeholder="Buscar..." oninput="filtraFlyout(this)" class="w-full px-3 py-2 rounded-lg bg-white/10 text-sm text-white placeholder-gray-400 outline-none border border-white/10 focus:border-cyan-400"><label class="flex items-center gap-2 text-xs text-gray-300 px-1 cursor-pointer select-none"><input type="checkbox" onchange="filtraFlyout(this)" class="rounded text-cyan-500"> Somente Emissão</label></div>
                        <div class="px-4 pt-3 pb-1 text-[11px] tracking-[0.2em] text-gray-500 font-semibold">—&nbsp; ACADÊMICO</div>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium">Cadastros Essenciais</p>
                        <a href="{{ Route::has('academico.calendarios.index') ? route('academico.calendarios.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.calendarios.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">35</span> Cadastro de Calendário</a>
                        <a href="{{ route('cadastros.index', 'escolas') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">8</span> Cadastro de Escola</a>
                        <a href="{{ Route::has('academico.grades-horario.index') ? route('academico.grades-horario.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.grades-horario.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">36</span> Cadastro de Grade de Horario</a>
                        <a href="{{ Route::has('academico.salas.index') ? route('academico.salas.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">39</span> Cadastro de Sala</a>
                        <a href="{{ Route::has('academico.turnos.index') ? route('academico.turnos.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">42</span> Cadastro de Turnos</a>
                        <a href="{{ Route::has('academico.configuracao.index') ? route('academico.configuracao.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.configuracao.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">167</span> Configuração do Acadêmico</a>
                        <a href="{{ Route::has('academico.configuracoes-boletim.index') ? route('academico.configuracoes-boletim.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.configuracoes-boletim.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">3</span> Configuração do Boletim</a>
                        <a href="{{ Route::has('academico.programacoes-avaliacao.index') ? route('academico.programacoes-avaliacao.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.programacoes-avaliacao.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">4</span> Programacoes de Avaliações</a>
                        <a href="{{ Route::has('academico.frequencia.index') ? route('academico.frequencia.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.frequencia.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">268</span> Registro de Frequência</a>
                        <a href="{{ Route::has('academico.tabelas-avaliacao.index') ? route('academico.tabelas-avaliacao.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.tabelas-avaliacao.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">5</span> Tabela de Avaliação</a>

                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Diploma Digital</p>
                        <a href="{{ Route::has('ged.diplomas.index') ? route('ged.diplomas.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">215</span> Cadastro de Diploma Digital</a>
                        <a href="{{ Route::has('academico.historico-digital.index') ? route('academico.historico-digital.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">226</span> Histórico Escolar Digital</a>

                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Documentos</p>
                        <a href="{{ Route::has('documentos.index') ? route('documentos.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">18</span> Cadastro de Documento</a>
                        <a href="{{ Route::has('academico.entregas-documento.consulta') ? route('academico.entregas-documento.consulta') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.entregas-documento.consulta') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">102</span> Consulta Docs não Entregues</a>
                        <a href="{{ Route::has('academico.emissoes.documentos') ? route('academico.emissoes.documentos') : '#' }}" target="_blank" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">210</span> Emissão de Documentos</a>
                        <a href="{{ Route::has('academico.entregas-documento.index') ? route('academico.entregas-documento.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.entregas-documento.index') || request()->routeIs('academico.entregas-documento.gerenciar') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">19</span> Entrega de Documentos</a>

                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Matrícula</p>
                        <a href="{{ route('cadastros.index', 'tags-matricula-online') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">74</span> Cadastro de Tag Matrícula Online</a>
                        <a href="{{ route('matricula-online.aberturas.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">140</span> Abertura de Matrícula Online</a>
                        <a href="{{ route('matricula-online.inscricoes.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">149</span> Acompanhamento de Inscrições</a>
                        <a href="{{ route('matricula-online.painel.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">151</span> Painel de Inscrições Online</a>
                        <a href="{{ route('matricula-online.cupons.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">182</span> Cadastro de Cupons de Desconto</a>
                        <a href="{{ route('matricula-online.emissao-inscricoes') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">187</span> Emissão de Inscrições</a>
                        <a href="{{ route('matricula-online.cupons-personalizados.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">193</span> Cadastro de Cupons Personalizados</a>
                        <a href="{{ route('alunos.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('alunos.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">17</span> Cadastro de Aluno</a>
                        <a href="{{ Route::has('academico.matriculas.index') ? route('academico.matriculas.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.matriculas.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">23</span> Matrícula e Histórico</a>
                        <a href="{{ route('cadastros.index', 'formas-ingresso') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">21</span> Forma de Ingresso</a>
                        <a href="{{ Route::has('academico.horas-complementares.index') ? route('academico.horas-complementares.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.horas-complementares.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">239</span> Controle de Horas Complementares</a>
                        <a href="{{ Route::has('academico.praticas-supervisionadas.index') ? route('academico.praticas-supervisionadas.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.praticas-supervisionadas.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">90</span> Controle de Prática Supervisionada</a>
                        <a href="{{ Route::has('academico.rematriculas.index') ? route('academico.rematriculas.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.rematriculas.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">279</span> Controle de Rematrículas</a>
                        <a href="{{ Route::has('academico.exames-nivel.index') ? route('academico.exames-nivel.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.exames-nivel.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">183</span> Manutenção de Exame de Nível</a>
                        <a href="{{ Route::has('academico.emissoes.index') ? route('academico.emissoes.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.emissoes.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">79</span> Emissão de Alunos Matriculados</a>
                        <a href="#" class="submenu-item block text-gray-500 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">98</span> Emissão do Histórico Escolar</a>
                        <a href="{{ route('cadastros.index', 'tags-matricula') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">169</span> Cadastro de Tag de Matrícula</a>
                        <a href="{{ route('cadastros.index', 'motivos-cancelamento') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">242</span> Motivo de Cancelamento Matrícula</a>
                        <a href="{{ Route::has('academico.emissoes.index') ? route('academico.emissoes.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">305</span> Emissão de Disciplinas dos Alunos</a>

                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Matriz Curricular</p>
                        <a href="{{ route('cadastros.index', 'areas') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">24</span> Cadastro de Área</a>
                        <a href="{{ Route::has('ged.atos.index') ? route('ged.atos.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">216</span> Cadastro de Atos Regulatorios</a>
                        <a href="{{ route('cadastros.index', 'conceitos-nota') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">176</span> Cadastro de Conceito de Notas</a>
                        <a href="{{ Route::has('academico.cursos.index') ? route('academico.cursos.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.cursos.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">25</span> Cadastro de Curso</a>
                        <a href="{{ Route::has('academico.disciplinas.index') ? route('academico.disciplinas.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.disciplinas.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">26</span> Cadastro de Disciplina</a>
                        <a href="{{ route('cadastros.index', 'graus') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">28</span> Cadastro de Grau</a>
                        <a href="{{ route('cadastros.index', 'habilitacoes') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">29</span> Cadastro de Habilitação</a>
                        <a href="{{ Route::has('academico.matrizes.index') ? route('academico.matrizes.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.matrizes.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">30</span> Cadastro de Matriz Curricular</a>
                        <a href="{{ route('cadastros.index', 'modulos') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">31</span> Cadastro de Módulos</a>
                        <a href="{{ Route::has('academico.emissoes.index') ? route('academico.emissoes.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">27</span> Emissão da Matriz Curricular</a>

                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Notas e Faltas</p>
                        <a href="{{ Route::has('academico.boletim.index') ? route('academico.boletim.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.boletim.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">2</span> Calculo do Boletim</a>
                        <a href="{{ Route::has('academico.emissoes.index') ? route('academico.emissoes.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">60</span> Emissão de Notas e Faltas</a>
                        <a href="{{ Route::has('academico.emissoes.index') ? route('academico.emissoes.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">91</span> Emissão do Diário de Classe</a>
                        <a href="{{ Route::has('academico.emissoes.index') ? route('academico.emissoes.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">249</span> Emissão de Pendencias Notas e Faltas</a>
                        <a href="{{ Route::has('academico.exclusao-notas.index') ? route('academico.exclusao-notas.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">137</span> Exclusão de Notas e Faltas</a>
                        <a href="{{ Route::has('academico.frequencia.index') ? route('academico.frequencia.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">16</span> Frequência e Conteudo Ministrado</a>
                        <a href="{{ Route::has('academico.lancamento-notas.index') ? route('academico.lancamento-notas.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.lancamento-notas.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">1</span> Lançamento de Avaliação</a>
                        <a href="{{ Route::has('academico.liberacoes-frequencia.index') ? route('academico.liberacoes-frequencia.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.liberacoes-frequencia.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">262</span> Liberar Lançamento de Frequência</a>

                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Plano de Ensino/Aula</p>
                        <a href="{{ Route::has('academico.estruturas-plano.index') ? route('academico.estruturas-plano.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.estruturas-plano.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">204</span> Cadastro de Estrutura do Plano</a>
                        <a href="{{ route('cadastros.index', 'topicos-plano') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">203</span> Cadastro de Tópico do Plano</a>
                        <a href="{{ Route::has('academico.planos-ensino.index') ? route('academico.planos-ensino.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.planos-ensino.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">119</span> Preenchimento Plano de Ensino</a>

                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Requerimentos</p>
                        <a href="{{ route('cadastros.index', 'tipos-requerimento') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">94</span> Cadastro de Requerimentos</a>
                        <a href="{{ Route::has('requerimentos.index') ? route('requerimentos.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">96</span> Manutenção de Requerimentos</a>

                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Turmas</p>
                        <a href="{{ Route::has('academico.periodos-letivos.index') ? route('academico.periodos-letivos.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">38</span> Cadastro de Período Letivo</a>
                        <a href="{{ Route::has('academico.turmas.index') ? route('academico.turmas.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.turmas.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">40</span> Cadastro de Turma</a>
                        <a href="{{ route('cadastros.index', 'tags-turma-montada') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">251</span> Cadastro de Tag (Turma Montada)</a>
                        <a href="{{ Route::has('academico.montagem-turma.index') ? route('academico.montagem-turma.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.montagem-turma.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">41</span> Montagem de Turma e Horario</a>
                        <a href="{{ Route::has('academico.emissoes.index') ? route('academico.emissoes.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">184</span> Emissão de Turmas Montadas</a>
                        <a href="{{ Route::has('academico.emissoes.index') ? route('academico.emissoes.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">185</span> Emissão de Horarios Professores</a>
                        <a href="{{ Route::has('academico.painel-professor.index') ? route('academico.painel-professor.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.painel-professor.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">257</span> Painel do Professor</a>
                        <a href="{{ Route::has('academico.planejamento-diario.index') ? route('academico.planejamento-diario.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('academico.planejamento-diario.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">45</span> Planejamento Diário de Aulas</a>
                        <a href="{{ Route::has('academico.emissoes.index') ? route('academico.emissoes.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">114</span> Declaracao de Aula Ministrada</a>
                        <a href="{{ Route::has('paineis.academico') ? route('paineis.academico') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('paineis.academico') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">144</span> Painel Acadêmico</a>
                    </div>
                </div>

                {{-- ADMINISTRATIVO --}}
                <div>
                    <button @click="openMod = openMod===1 ? null : 1" class="w-[calc(100%-16px)] mx-2 my-0.5 rounded-xl flex flex-col items-center justify-center gap-1.5 py-2.5 text-[10px] leading-tight transition-colors hover:bg-white/10 {{ request()->is('admin*') || request()->routeIs('painel-cliente.*') ? 'bg-gray-200 text-gray-900' : 'text-gray-400' }}" :class="openMod===1 ? 'bg-gray-200 text-gray-900' : ''"><i class="fa-solid fa-building text-lg"></i><span class="text-center px-0.5">Administrativo</span></button>
                    <div x-show="openMod===1" x-cloak class="fixed left-28 top-0 z-50 w-80 h-screen bg-[#2a2f36] shadow-2xl border border-black/30 overflow-y-auto py-2"><div class="px-3 pb-2 pt-1 space-y-2"><input type="text" placeholder="Buscar..." oninput="filtraFlyout(this)" class="w-full px-3 py-2 rounded-lg bg-white/10 text-sm text-white placeholder-gray-400 outline-none border border-white/10 focus:border-cyan-400"><label class="flex items-center gap-2 text-xs text-gray-300 px-1 cursor-pointer select-none"><input type="checkbox" onchange="filtraFlyout(this)" class="rounded text-cyan-500"> Somente Emissão</label></div>
                        <div class="px-4 pt-3 pb-1 text-[11px] tracking-[0.2em] text-gray-500 font-semibold">—&nbsp; ADMINISTRATIVO</div>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium">Acessos</p>
                        <a href="{{ Route::has('admin.grupos.index') ? route('admin.grupos.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('admin.grupos.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">43</span> Cadastro de Grupo de Operadores</a>
                        <a href="{{ Route::has('admin.operadores.index') ? route('admin.operadores.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('admin.operadores.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">44</span> Cadastro de Operador</a>
                        <a href="{{ route('painel-cliente.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('painel-cliente.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">112</span> Painel do Cliente</a>
                    </div>
                </div>

                {{-- BIBLIOTECA --}}
                <div>
                    <button @click="openMod = openMod===2 ? null : 2" class="w-[calc(100%-16px)] mx-2 my-0.5 rounded-xl flex flex-col items-center justify-center gap-1.5 py-2.5 text-[10px] leading-tight transition-colors hover:bg-white/10 {{ request()->is('biblioteca*') ? 'bg-gray-200 text-gray-900' : 'text-gray-400' }}" :class="openMod===2 ? 'bg-gray-200 text-gray-900' : ''"><i class="fa-solid fa-book text-lg"></i><span class="text-center px-0.5">Biblioteca</span></button>
                    <div x-show="openMod===2" x-cloak class="fixed left-28 top-0 z-50 w-80 h-screen bg-[#2a2f36] shadow-2xl border border-black/30 overflow-y-auto py-2"><div class="px-3 pb-2 pt-1 space-y-2"><input type="text" placeholder="Buscar..." oninput="filtraFlyout(this)" class="w-full px-3 py-2 rounded-lg bg-white/10 text-sm text-white placeholder-gray-400 outline-none border border-white/10 focus:border-cyan-400"><label class="flex items-center gap-2 text-xs text-gray-300 px-1 cursor-pointer select-none"><input type="checkbox" onchange="filtraFlyout(this)" class="rounded text-cyan-500"> Somente Emissão</label></div>
                        <div class="px-4 pt-3 pb-1 text-[11px] tracking-[0.2em] text-gray-500 font-semibold">—&nbsp; BIBLIOTECA</div>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium">Acervo</p>
                        <a href="{{ Route::has('biblioteca.obras.index') ? route('biblioteca.obras.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('biblioteca.obras.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">288</span> Cadastro de Obra</a>
                        <a href="{{ Route::has('biblioteca.exemplares.index') ? route('biblioteca.exemplares.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('biblioteca.exemplares.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">286</span> Cadastro de Exemplares</a>
                        <a href="{{ Route::has('biblioteca.movimentacoes.index') ? route('biblioteca.movimentacoes.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('biblioteca.movimentacoes.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">287</span> Movimentações de Exemplares</a>
                        <a href="{{ Route::has('biblioteca.reservas.index') ? route('biblioteca.reservas.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('biblioteca.reservas.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">289</span> Reserva de Exemplares</a>
                        <a href="{{ Route::has('biblioteca.emissoes.etiquetas') ? route('biblioteca.emissoes.etiquetas') : '#' }}" target="_blank" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">283</span> Emissão de Etiquetas</a>
                        <a href="{{ Route::has('biblioteca.emissoes.exemplares') ? route('biblioteca.emissoes.exemplares') : '#' }}" target="_blank" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">284</span> Emissão de Exemplares</a>
                        <a href="{{ Route::has('biblioteca.emissoes.movimentacoes') ? route('biblioteca.emissoes.movimentacoes') : '#' }}" target="_blank" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">285</span> Emissão de Movimentações</a>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Cadastros Essenciais</p>
                        <a href="{{ route('cadastros.index', 'autores') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">290</span> Cadastro de Autores</a>
                        <a href="{{ route('cadastros.index', 'bibliotecas') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">291</span> Cadastro de Biblioteca</a>
                        <a href="{{ route('cadastros.index', 'colecoes') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">292</span> Cadastro de Colecao</a>
                        <a href="{{ route('cadastros.index', 'editores') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">293</span> Cadastro de Editores</a>
                        <a href="{{ route('cadastros.index', 'estados-conservacao') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">294</span> Estado de Conservacao</a>
                        <a href="{{ route('cadastros.index', 'idiomas') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">295</span> Cadastro de Idiomas</a>
                        <a href="{{ route('cadastros.index', 'tipos-aquisicao') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">297</span> Tipo de Aquisicao</a>
                        <a href="{{ route('cadastros.index', 'tipos-material') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">298</span> Tipo de Material</a>
                        <a href="{{ route('cadastros.index', 'motivos-indisponibilidade') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">296</span> Motivo de Indisponibilidade</a>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Configuração</p>
                        <a href="{{ Route::has('biblioteca.configuracao.index') ? route('biblioteca.configuracao.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('biblioteca.configuracao.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">299</span> Configuração do Biblioteca</a>
                    </div>
                </div>

                {{-- COMUNICACAO --}}
                <div>
                    <button @click="openMod = openMod===3 ? null : 3" class="w-[calc(100%-16px)] mx-2 my-0.5 rounded-xl flex flex-col items-center justify-center gap-1.5 py-2.5 text-[10px] leading-tight transition-colors hover:bg-white/10 {{ request()->is('comunicacao*') ? 'bg-gray-200 text-gray-900' : 'text-gray-400' }}" :class="openMod===3 ? 'bg-gray-200 text-gray-900' : ''"><i class="fa-solid fa-comments text-lg"></i><span class="text-center px-0.5">Comunicação</span></button>
                    <div x-show="openMod===3" x-cloak class="fixed left-28 top-0 z-50 w-80 h-screen bg-[#2a2f36] shadow-2xl border border-black/30 overflow-y-auto py-2"><div class="px-3 pb-2 pt-1 space-y-2"><input type="text" placeholder="Buscar..." oninput="filtraFlyout(this)" class="w-full px-3 py-2 rounded-lg bg-white/10 text-sm text-white placeholder-gray-400 outline-none border border-white/10 focus:border-cyan-400"><label class="flex items-center gap-2 text-xs text-gray-300 px-1 cursor-pointer select-none"><input type="checkbox" onchange="filtraFlyout(this)" class="rounded text-cyan-500"> Somente Emissão</label></div>
                        <div class="px-4 pt-3 pb-1 text-[11px] tracking-[0.2em] text-gray-500 font-semibold">—&nbsp; COMUNICAÇÃO</div>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium">Configuração</p>
                        <a href="{{ route('comunicacao.notificacoes.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">260</span> Central de Notificação do Aluno</a>
                        <a href="{{ route('comunicacao.configuracao.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">85</span> Configuração da Comunicação</a>
                        <a href="{{ route('comunicacao.saldo-sms') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">89</span> Consulta de Saldo SMS</a>
                        <a href="{{ route('cadastros.index', 'numeros-whatsapp') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">247</span> Números Whatsapp</a>
                        <a href="{{ route('comunicacao.templates.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">87</span> Templates de Mensagens</a>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Mensagens</p>
                        <a href="{{ route('comunicacao.mensagens.avulsa') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">84</span> Mensagens Avulsas</a>
                        <a href="{{ route('comunicacao.mensagens.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">88</span> Mensagens de Aviso de Cobrança</a>
                        <a href="{{ route('comunicacao.mensagens.aviso-pagamento') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">234</span> Mensagens de Aviso de Pagamento</a>
                        <a href="{{ route('comunicacao.mensagens.avisos') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">86</span> Mensagens de Aviso de Vencimento</a>
                        <a href="{{ route('comunicacao.mensagens.interessados') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">62</span> Mensagens para Interessados CRM</a>
                    </div>
                </div>

                {{-- ESTOQUE --}}
                <div>
                    <button @click="openMod = openMod===4 ? null : 4" class="w-[calc(100%-16px)] mx-2 my-0.5 rounded-xl flex flex-col items-center justify-center gap-1.5 py-2.5 text-[10px] leading-tight transition-colors hover:bg-white/10 {{ request()->is('estoque*') ? 'bg-gray-200 text-gray-900' : 'text-gray-400' }}" :class="openMod===4 ? 'bg-gray-200 text-gray-900' : ''"><i class="fa-solid fa-boxes-stacked text-lg"></i><span class="text-center px-0.5">Estoque</span></button>
                    <div x-show="openMod===4" x-cloak class="fixed left-28 top-0 z-50 w-80 h-screen bg-[#2a2f36] shadow-2xl border border-black/30 overflow-y-auto py-2"><div class="px-3 pb-2 pt-1 space-y-2"><input type="text" placeholder="Buscar..." oninput="filtraFlyout(this)" class="w-full px-3 py-2 rounded-lg bg-white/10 text-sm text-white placeholder-gray-400 outline-none border border-white/10 focus:border-cyan-400"><label class="flex items-center gap-2 text-xs text-gray-300 px-1 cursor-pointer select-none"><input type="checkbox" onchange="filtraFlyout(this)" class="rounded text-cyan-500"> Somente Emissão</label></div>
                        <div class="px-4 pt-3 pb-1 text-[11px] tracking-[0.2em] text-gray-500 font-semibold">—&nbsp; ESTOQUE</div>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium">Cadastros Essenciais</p>
                        <a href="{{ Route::has('estoque.categorias.index') ? route('estoque.categorias.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">147</span> Cadastro de Categorias de Estoque</a>
                        <a href="{{ route('cadastros.index', 'depositos') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">153</span> Cadastro de Depositos de Estoque</a>
                        <a href="{{ route('estoque.produtos.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('estoque.produtos.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">148</span> Cadastro de Produtos de Estoque</a>
                        <a href="{{ Route::has('estoque.unidades.index') ? route('estoque.unidades.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">146</span> Cadastro de Unidades de Medida</a>
                        <a href="{{ route('estoque.emissao') }}" target="_blank" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">186</span> Emissão de Produtos de Estoques</a>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Movimentações</p>
                        <a href="{{ route('estoque.consulta.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('estoque.consulta.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">154</span> Consulta de Estoque</a>
                        <a href="{{ Route::has('estoque.movimentacoes.index') ? route('estoque.movimentacoes.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">150</span> Movimentações de Estoque</a>
                    </div>
                </div>

                {{-- CRM --}}
                <div>
                    <button @click="openMod = openMod===5 ? null : 5" class="w-[calc(100%-16px)] mx-2 my-0.5 rounded-xl flex flex-col items-center justify-center gap-1.5 py-2.5 text-[10px] leading-tight transition-colors hover:bg-white/10 {{ request()->is('crm*') ? 'bg-gray-200 text-gray-900' : 'text-gray-400' }}" :class="openMod===5 ? 'bg-gray-200 text-gray-900' : ''"><i class="fa-solid fa-handshake text-lg"></i><span class="text-center px-0.5">CRM</span></button>
                    <div x-show="openMod===5" x-cloak class="fixed left-28 top-0 z-50 w-80 h-screen bg-[#2a2f36] shadow-2xl border border-black/30 overflow-y-auto py-2"><div class="px-3 pb-2 pt-1 space-y-2"><input type="text" placeholder="Buscar..." oninput="filtraFlyout(this)" class="w-full px-3 py-2 rounded-lg bg-white/10 text-sm text-white placeholder-gray-400 outline-none border border-white/10 focus:border-cyan-400"><label class="flex items-center gap-2 text-xs text-gray-300 px-1 cursor-pointer select-none"><input type="checkbox" onchange="filtraFlyout(this)" class="rounded text-cyan-500"> Somente Emissão</label></div>
                        <div class="px-4 pt-3 pb-1 text-[11px] tracking-[0.2em] text-gray-500 font-semibold">—&nbsp; CRM</div>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium">Cadastros Essenciais</p>
                        <a href="{{ route('cadastros.index', 'acoes-automaticas') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">256</span> Cadastro de Ação Automática (CRM)</a>
                        <a href="{{ route('cadastros.index', 'categorias-interessado') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">207</span> Cadastro de Categorias (Interessados)</a>
                        <a href="{{ route('crm.eventos.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">104</span> Cadastro de Eventos CRM</a>
                        <a href="{{ route('crm.funil.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">200</span> Cadastro de Funil de Oportunidades</a>
                        <a href="{{ route('crm.metas.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">191</span> Cadastro de Metas CRM</a>
                        <a href="{{ route('cadastros.index', 'motivos-ganho') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">212</span> Cadastro de Motivo de Ganho</a>
                        <a href="{{ route('cadastros.index', 'motivos-pausa') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">202</span> Cadastro de Motivo de Pausa</a>
                        <a href="{{ route('cadastros.index', 'motivos-perda') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">107</span> Cadastro de Motivos de Perda</a>
                        <a href="{{ route('crm.origens.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">103</span> Cadastro de Origem do Interessado</a>
                        <a href="{{ route('cadastros.index', 'produtos-servico') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">206</span> Cadastro de Produtos/Serviços CRM</a>
                        <a href="{{ route('crm.tags.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">171</span> Cadastro de Tag CRM</a>
                        <a href="{{ route('crm.configuracoes.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">166</span> Configuração do CRM</a>
                        <a href="{{ route('cadastros.index', 'motivos-finalizacao-atividade') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">264</span> Motivo de Finalização de Atividade (CRM)</a>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Oportunidades</p>
                        <a href="{{ route('crm.interessados.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">108</span> Cadastro de Interessados (CRM)</a>
                        <a href="{{ route('crm.desempenho.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">190</span> Desempenho Individual do Consultor</a>
                        <a href="{{ route('geral.emissoes.atividades-crm') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">263</span> Emissão de Atividades (CRM)</a>
                        <a href="{{ route('crm.propostas.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">201</span> Emissão de Propostas (CRM)</a>
                        <a href="{{ route('crm.exportacao.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">159</span> Exportação de Oportunidades (CRM)</a>
                        <a href="{{ route('crm.funil.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">110</span> Funil de Oportunidades (CRM)</a>
                        <a href="{{ route('crm.oportunidades.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">109</span> Manutenção de Oportunidades (CRM)</a>
                    </div>
                </div>

                {{-- EAD --}}
                <div>
                    <button @click="openMod = openMod===6 ? null : 6" class="w-[calc(100%-16px)] mx-2 my-0.5 rounded-xl flex flex-col items-center justify-center gap-1.5 py-2.5 text-[10px] leading-tight transition-colors hover:bg-white/10 {{ request()->is('ead*') ? 'bg-gray-200 text-gray-900' : 'text-gray-400' }}" :class="openMod===6 ? 'bg-gray-200 text-gray-900' : ''"><i class="fa-solid fa-laptop text-lg"></i><span class="text-center px-0.5">EAD</span></button>
                    <div x-show="openMod===6" x-cloak class="fixed left-28 top-0 z-50 w-80 h-screen bg-[#2a2f36] shadow-2xl border border-black/30 overflow-y-auto py-2"><div class="px-3 pb-2 pt-1 space-y-2"><input type="text" placeholder="Buscar..." oninput="filtraFlyout(this)" class="w-full px-3 py-2 rounded-lg bg-white/10 text-sm text-white placeholder-gray-400 outline-none border border-white/10 focus:border-cyan-400"><label class="flex items-center gap-2 text-xs text-gray-300 px-1 cursor-pointer select-none"><input type="checkbox" onchange="filtraFlyout(this)" class="rounded text-cyan-500"> Somente Emissão</label></div>
                        <div class="px-4 pt-3 pb-1 text-[11px] tracking-[0.2em] text-gray-500 font-semibold">—&nbsp; EAD</div>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium">Cadastros Essenciais</p>
                        <a href="{{ route('ead.cursos.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('ead.cursos.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">152</span> Cadastro de Curso (EAD)</a>
                        <a href="{{ route('cadastros.index', 'agrupadores-curso') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">211</span> Cadastro de Agrupador de Cursos</a>
                        <a href="{{ route('ead.sub-agrupadores.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('ead.sub-agrupadores.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">266</span> Cadastro de Sub Agrupador (EAD)</a>
                        <a href="{{ route('cadastros.index', 'tags-curso-ead') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">246</span> Cadastro de Tag (Curso EAD)</a>
                        <a href="{{ Route::has('ead.avaliacoes.index') ? route('ead.avaliacoes.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('ead.avaliacoes.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">214</span> Cadastro de Avaliações EAD</a>
                        <a href="{{ Route::has('ead.questoes.index') ? route('ead.questoes.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('ead.questoes.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">238</span> Cadastro de Questões Avulsas</a>
                        <a href="{{ route('cadastros.index', 'tags-questao') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">236</span> Cadastro de Tag de Questões</a>
                        <a href="{{ route('ead.geradores.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('ead.geradores.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">241</span> Gerador de Avaliações</a>
                        <a href="{{ Route::has('ead.videos.index') ? route('ead.videos.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('ead.videos.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">301</span> Cadastro de Vídeos</a>
                        <a href="{{ route('ead.foruns.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('ead.foruns.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">306</span> Fóruns EAD</a>
                        <a href="{{ Route::has('ead.matriculas.index') ? route('ead.matriculas.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('ead.matriculas.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">156</span> Manutenção de Matriculas EAD</a>
                        <a href="{{ route('ead.emissoes.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('ead.emissoes.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">174</span> Emissão de Alunos Matriculados EAD</a>
                        <a href="{{ route('ead.emissoes.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('ead.emissoes.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">219</span> Emissão de notas alunos (EAD)</a>
                    </div>
                </div>

                {{-- FINANCEIRO --}}
                <div>
                    <button @click="openMod = openMod===7 ? null : 7" class="w-[calc(100%-16px)] mx-2 my-0.5 rounded-xl flex flex-col items-center justify-center gap-1.5 py-2.5 text-[10px] leading-tight transition-colors hover:bg-white/10 {{ request()->is('financeiro*') ? 'bg-gray-200 text-gray-900' : 'text-gray-400' }}" :class="openMod===7 ? 'bg-gray-200 text-gray-900' : ''"><i class="fa-solid fa-dollar-sign text-lg"></i><span class="text-center px-0.5">Financeiro</span></button>
                    <div x-show="openMod===7" x-cloak class="fixed left-28 top-0 z-50 w-80 h-screen bg-[#2a2f36] shadow-2xl border border-black/30 overflow-y-auto py-2"><div class="px-3 pb-2 pt-1 space-y-2"><input type="text" placeholder="Buscar..." oninput="filtraFlyout(this)" class="w-full px-3 py-2 rounded-lg bg-white/10 text-sm text-white placeholder-gray-400 outline-none border border-white/10 focus:border-cyan-400"><label class="flex items-center gap-2 text-xs text-gray-300 px-1 cursor-pointer select-none"><input type="checkbox" onchange="filtraFlyout(this)" class="rounded text-cyan-500"> Somente Emissão</label></div>
                        <div class="px-4 pt-3 pb-1 text-[11px] tracking-[0.2em] text-gray-500 font-semibold">—&nbsp; FINANCEIRO</div>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium">Cadastros Essenciais</p>
                        <a href="{{ route('cadastros.index', 'centros-custo') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">274</span> Cadastro de Centro de Custos</a>
                        <a href="{{ route('cadastros.index', 'bancos') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">47</span> Cadastro de Banco</a>
                        <a href="{{ route('financeiro.categorias-pagar.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">51</span> Cadastro de Categorias (A Pagar)</a>
                        <a href="{{ route('financeiro.categorias-receber.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">65</span> Cadastro de Categorias (A Receber)</a>
                        <a href="{{ route('financeiro.nfse.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">227</span> Cadastro de Configurações de NFS-e</a>
                        <a href="{{ route('financeiro.contas-bancarias.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">63</span> Cadastro de Contas</a>
                        <a href="{{ route('financeiro.descontos-condicionais.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">58</span> Cadastro de Desconto Condicional</a>
                        <a href="{{ route('financeiro.descontos.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">57</span> Cadastro de Desconto Incondicional</a>
                        <a href="{{ route('cadastros.index', 'formas-pagamento') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">53</span> Cadastro de Forma de Pagamento</a>
                        <a href="#" class="submenu-item block text-gray-500 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">213</span> Cadastro de Taxas de Cartão Avulso</a>
                        <a href="{{ route('financeiro.plano-contas.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">50</span> Cadastro do Plano de Contas</a>
                        <a href="{{ route('financeiro.configuracao.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">59</span> Configuração do Financeiro</a>
                        <a href="{{ route('financeiro.emissoes.plano-contas') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">162</span> Emissão do Plano de Contas</a>
                        <a href="{{ route('cadastros.index', 'grupos-categoria-pagar') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">243</span> Grupo de Categorias (A Pagar)</a>
                        <a href="{{ route('cadastros.index', 'motivos-restricao') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">261</span> Motivo de Restrição</a>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Caixa (Movimentações)</p>
                        <a href="{{ route('financeiro.emissoes.fechamento-caixa') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">106</span> Emissão do Fechamento de Caixa</a>
                        <a href="{{ route('financeiro.caixas.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">68</span> Movimentações de Caixas</a>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Cartões</p>
                        <a href="{{ route('financeiro.contratos-cartao.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">70</span> Cadastro de Contratos de Cartões</a>
                        <a href="{{ route('financeiro.cartoes-empresariais.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">136</span> Cartão de Crédito Empresarial</a>
                        <a href="{{ route('financeiro.conciliacao-cartao.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">71</span> Conciliação de Recebimentos (Cartão)</a>
                        <a href="{{ route('financeiro.emissoes.resumo-cartao') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">255</span> Resumo de Recebimentos (Cartão)</a>
                        <a href="#" class="submenu-item block text-gray-500 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">163</span> Transações com Cartão (Automático)</a>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Cheques</p>
                        <a href="{{ route('financeiro.cheques.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">72</span> Manutenção de Cheques</a>
                        <a href="{{ route('cadastros.index', 'motivos-devolucao-cheque') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">73</span> Motivo de Devolução (Cheque)</a>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Lançamentos Financeiros</p>
                        <a href="{{ route('financeiro.dre.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">111</span> Demonstrativo de Resultados (DRE)</a>
                        <a href="{{ route('financeiro.emissoes.lancamentos') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">161</span> Emissão de Lançamentos Financeiros</a>
                        <a href="{{ route('financeiro.fluxo-caixa.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">195</span> Fluxo de Caixa (Diário)</a>
                        <a href="{{ route('financeiro.fluxo-caixa.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">78</span> Fluxo de Caixa (Mensal)</a>
                        <a href="{{ route('financeiro.lancamentos.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">61</span> Lançamentos Financeiros</a>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Títulos a pagar</p>
                        <a href="{{ route('financeiro.comissoes.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">222</span> Cálculo de Comissões</a>
                        <a href="#" class="submenu-item block text-gray-500 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">302</span> Cálculos de Hora-aula</a>
                        <a href="{{ route('financeiro.emissoes.comissoes') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">180</span> Emissão de Comissões</a>
                        <a href="{{ route('financeiro.emissoes.pagamentos-contas-pagar') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">258</span> Emissão de pagamentos Contas a Pagar</a>
                        <a href="{{ route('financeiro.emissoes.titulos-pagar') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">173</span> Emissão de Títulos a Pagar</a>
                        <a href="{{ route('financeiro.titulos-pagar.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">52</span> Manutenção de Títulos a Pagar</a>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Títulos a receber</p>
                        <a href="{{ route('financeiro.atualizacao-indice.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">175</span> Atualização de Parcelas pelo Índice</a>
                        <a href="{{ route('cadastros.index', 'agrupadores-titulo') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">217</span> Cadastro de Agrupador de Títulos</a>
                        <a href="{{ route('financeiro.emissoes.conta-corrente') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">93</span> Conta Corrente Por Pessoa</a>
                        <a href="{{ route('financeiro.emissoes.boletos') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">66</span> Emissão de Boletos Bancários</a>
                        <a href="{{ route('financeiro.emissoes.cobranca') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">113</span> Emissão de Cobrança</a>
                        <a href="{{ route('financeiro.emissoes.conta-corrente') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">192</span> Emissão de Conta Corrente Pessoa</a>
                        <a href="{{ route('financeiro.emissoes.declaracao-pagamentos') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">99</span> Emissão de Declaração de Pagamentos</a>
                        <a href="{{ route('financeiro.emissoes.renegociacoes') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">275</span> Emissão de Renegociação de Parcelas</a>
                        <a href="{{ route('financeiro.emissoes.titulos-receber') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">116</span> Emissão de Títulos a Receber</a>
                        <a href="{{ route('financeiro.link-pagamento.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">230</span> Link de Pagamento Avulso</a>
                        <a href="{{ route('financeiro.titulos-receber.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">64</span> Manutenção de Títulos a Receber</a>
                        <a href="{{ route('financeiro.recebimento-coletivo.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">259</span> Recebimento Coletivo (Bancário)</a>
                        <a href="{{ route('financeiro.renegociacoes.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">80</span> Renegociações de Parcelas</a>
                        <a href="{{ route('financeiro.emissoes.resumo-pessoa') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">101</span> Resumo Financeiro da Pessoa</a>
                    </div>
                </div>

                {{-- GED --}}
                <div>
                    <button @click="openMod = openMod===8 ? null : 8" class="w-[calc(100%-16px)] mx-2 my-0.5 rounded-xl flex flex-col items-center justify-center gap-1.5 py-2.5 text-[10px] leading-tight transition-colors hover:bg-white/10 {{ request()->is('ged*') ? 'bg-gray-200 text-gray-900' : 'text-gray-400' }}" :class="openMod===8 ? 'bg-gray-200 text-gray-900' : ''"><i class="fa-solid fa-folder-open text-lg"></i><span class="text-center px-0.5">GED</span></button>
                    <div x-show="openMod===8" x-cloak class="fixed left-28 top-0 z-50 w-80 h-screen bg-[#2a2f36] shadow-2xl border border-black/30 overflow-y-auto py-2"><div class="px-3 pb-2 pt-1 space-y-2"><input type="text" placeholder="Buscar..." oninput="filtraFlyout(this)" class="w-full px-3 py-2 rounded-lg bg-white/10 text-sm text-white placeholder-gray-400 outline-none border border-white/10 focus:border-cyan-400"><label class="flex items-center gap-2 text-xs text-gray-300 px-1 cursor-pointer select-none"><input type="checkbox" onchange="filtraFlyout(this)" class="rounded text-cyan-500"> Somente Emissão</label></div>
                        <div class="px-4 pt-3 pb-1 text-[11px] tracking-[0.2em] text-gray-500 font-semibold">—&nbsp; GED</div>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium">Cadastros Essenciais</p>
                        <a href="{{ Route::has('ged.documentos.index') ? route('ged.documentos.index') : route('ged.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->is('ged*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">244</span> Documento (GED)</a>
                        <a href="#" class="submenu-item block text-gray-500 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">245</span> Categoria do Documento (GED)</a>
                        <a href="#" class="submenu-item block text-gray-500 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">252</span> Tipo de Documento</a>
                    </div>
                </div>

                {{-- GERAL --}}
                <div>
                    <button @click="openMod = openMod===9 ? null : 9" class="w-[calc(100%-16px)] mx-2 my-0.5 rounded-xl flex flex-col items-center justify-center gap-1.5 py-2.5 text-[10px] leading-tight transition-colors hover:bg-white/10 {{ request()->is('geral*') || request()->is('pessoas*') || request()->is('profissionais*') || request()->is('atendimentos*') ? 'bg-gray-200 text-gray-900' : 'text-gray-400' }}" :class="openMod===9 ? 'bg-gray-200 text-gray-900' : ''"><i class="fa-solid fa-table-cells text-lg"></i><span class="text-center px-0.5">Geral</span></button>
                    <div x-show="openMod===9" x-cloak class="fixed left-28 top-0 z-50 w-80 h-screen bg-[#2a2f36] shadow-2xl border border-black/30 overflow-y-auto py-2"><div class="px-3 pb-2 pt-1 space-y-2"><input type="text" placeholder="Buscar..." oninput="filtraFlyout(this)" class="w-full px-3 py-2 rounded-lg bg-white/10 text-sm text-white placeholder-gray-400 outline-none border border-white/10 focus:border-cyan-400"><label class="flex items-center gap-2 text-xs text-gray-300 px-1 cursor-pointer select-none"><input type="checkbox" onchange="filtraFlyout(this)" class="rounded text-cyan-500"> Somente Emissão</label></div>
                        <div class="px-4 pt-3 pb-1 text-[11px] tracking-[0.2em] text-gray-500 font-semibold">—&nbsp; GERAL</div>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium">Atendimentos</p>
                        <a href="#" class="submenu-item block text-gray-500 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">172</span> Atendimentos Pool (Follow up)</a>
                        <a href="{{ route('cadastros.index', 'categorias-atendimento') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">54</span> Cadastro de Categorias (Atendimento)</a>
                        <a href="{{ route('cadastros.index', 'motivos-falha-atendimento') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">178</span> Cadastro Motivos de Falha (Atendimentos)</a>
                        <a href="{{ route('geral.emissoes.atendimentos') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">235</span> Emissão de Atendimentos</a>
                        <a href="{{ route('atendimentos.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">55</span> Manutenção de Atendimentos</a>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Cadastros Essenciais</p>
                        <a href="{{ route('cadastros.index', 'atributos-adicionais') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">97</span> Cadastro de Atributos Adicionais</a>
                        <a href="{{ route('admin.departamentos.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">67</span> Cadastro de Departamento</a>
                        <a href="{{ route('cadastros.index', 'instituicoes') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">7</span> Cadastro de Instituição de Ensino</a>
                        <a href="{{ route('geral.questionarios.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">118</span> Cadastro de Questionários NPS</a>
                        <a href="#" class="submenu-item block text-gray-500 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">276</span> Consulta de CPF Por Base</a>
                        <a href="{{ route('geral.consultas.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">221</span> Consulta Personalizada</a>
                        <a href="{{ route('geral.consultas.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">224</span> Emissão de Consulta Personalizada</a>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Configurações de Emissões</p>
                        <a href="{{ route('cadastros.index', 'assinaturas') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">6</span> Cadastro de Assinatura</a>
                        <a href="{{ route('geral.modelos-documento.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">9</span> Cadastro de Modelo de Documentos</a>
                        <a href="{{ route('cadastros.index', 'modelos-papel') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">49</span> Cadastro de Modelo de Papel</a>
                        <a href="{{ route('cadastros.index', 'cabecalhos') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">48</span> Cadastros de Modelos de Cabeçalho</a>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Indicação</p>
                        <a href="{{ route('geral.campanhas-indicacao.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">225</span> Campanha de Indicação</a>
                        <a href="{{ route('geral.indicacoes.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">223</span> Controle de Indicações</a>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Pessoas</p>
                        <a href="{{ route('geral.aniversariantes.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">164</span> Aniversariantes</a>
                        <a href="{{ route('cadastros.index', 'alergias') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">198</span> Cadastro de Alergia</a>
                        <a href="{{ route('cadastros.index', 'necessidades-especiais') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">10</span> Cadastro de Necessidades Especiais</a>
                        <a href="{{ route('pessoas.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">11</span> Cadastro de Pessoa</a>
                        <a href="{{ route('profissionais.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">12</span> Cadastro de Profissional</a>
                        <a href="{{ route('cadastros.index', 'profissoes') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">145</span> Cadastro de Profissões</a>
                        <a href="{{ route('cadastros.index', 'religioes') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">13</span> Cadastro de Religião</a>
                        <a href="{{ route('cadastros.index', 'tipos-profissional') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">14</span> Cadastro de Tipo de Profissional</a>
                        <a href="{{ route('cadastros.index', 'titularidades') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">15</span> Cadastro de Titularidade</a>
                        <a href="#" class="submenu-item block text-gray-500 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">123</span> Emissão de Contratos Avulsos</a>
                        <a href="{{ route('geral.emissoes.pessoas') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">254</span> Emissão de Pessoas</a>
                        <a href="{{ route('geral.emissoes.professores') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">181</span> Emissão de Professores</a>
                        <a href="{{ route('geral.emissoes.profissionais') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">131</span> Emissão de Profissionais</a>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Questionário</p>
                        <a href="{{ route('geral.questoes.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">32</span> Cadastro de Opções</a>
                        <a href="{{ route('geral.questionarios.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">34</span> Cadastro de Questionário</a>
                        <a href="{{ route('geral.questoes.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">33</span> Cadastro de Questões</a>
                    </div>
                </div>

                {{-- INTEGRACOES --}}
                <div>
                    <button @click="openMod = openMod===12 ? null : 12" class="w-[calc(100%-16px)] mx-2 my-0.5 rounded-xl flex flex-col items-center justify-center gap-1.5 py-2.5 text-[10px] leading-tight transition-colors hover:bg-white/10 {{ request()->is('integracoes*') ? 'bg-gray-200 text-gray-900' : 'text-gray-400' }}" :class="openMod===12 ? 'bg-gray-200 text-gray-900' : ''"><i class="fa-solid fa-plug text-lg"></i><span class="text-center px-0.5">Integrações</span></button>
                    <div x-show="openMod===12" x-cloak class="fixed left-28 top-0 z-50 w-80 h-screen bg-[#2a2f36] shadow-2xl border border-black/30 overflow-y-auto py-2"><div class="px-3 pb-2 pt-1 space-y-2"><input type="text" placeholder="Buscar..." oninput="filtraFlyout(this)" class="w-full px-3 py-2 rounded-lg bg-white/10 text-sm text-white placeholder-gray-400 outline-none border border-white/10 focus:border-cyan-400"><label class="flex items-center gap-2 text-xs text-gray-300 px-1 cursor-pointer select-none"><input type="checkbox" onchange="filtraFlyout(this)" class="rounded text-cyan-500"> Somente Emissão</label></div>
                        <div class="px-4 pt-3 pb-1 text-[11px] tracking-[0.2em] text-gray-500 font-semibold">—&nbsp; INTEGRAÇÕES</div>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium">Integração Bancária</p>
                        <a href="{{ route('financeiro.titulos-receber.remessa') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">56</span> Geração do Arquivo de Remessa</a>
                        <a href="#" class="submenu-item block text-gray-500 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">278</span> Geração do Arquivo de Remessa (A pagar)</a>
                        <a href="{{ route('financeiro.retorno.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">82</span> Importação do Arquivo de Retorno</a>
                        <a href="#" class="submenu-item block text-gray-500 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">120</span> Importação do Arquivo de Retorno (a pagar)</a>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Nota Fiscal</p>
                        <a href="#" class="submenu-item block text-gray-500 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">220</span> Inutilização de Numeração de NFe</a>
                        <a href="{{ route('financeiro.nfse.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">83</span> Manutenção de Notas Fiscais</a>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">PJBank</p>
                        <a href="#" class="submenu-item block text-gray-500 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">117</span> Inconsistência de Boleto (Automáticos)</a>
                        <a href="#" class="submenu-item block text-gray-500 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">165</span> Inconsistência de Cartão (Automático)</a>
                        <a href="#" class="submenu-item block text-gray-500 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">209</span> Recebimentos de Boletos Automáticos</a>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">RD Station</p>
                        <a href="{{ route('integracoes.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">231</span> Histórico do RD Station</a>
                    </div>
                </div>

                {{-- MATRICULA ONLINE --}}
                <div>
                    <button @click="openMod = openMod===10 ? null : 10" class="w-[calc(100%-16px)] mx-2 my-0.5 rounded-xl flex flex-col items-center justify-center gap-1.5 py-2.5 text-[10px] leading-tight transition-colors hover:bg-white/10 {{ request()->is('matricula-online*') ? 'bg-gray-200 text-gray-900' : 'text-gray-400' }}" :class="openMod===10 ? 'bg-gray-200 text-gray-900' : ''"><i class="fa-solid fa-globe text-lg"></i><span class="text-center px-0.5">Matr. Online</span></button>
                    <div x-show="openMod===10" x-cloak class="fixed left-28 top-0 z-50 w-80 h-screen bg-[#2a2f36] shadow-2xl border border-black/30 overflow-y-auto py-2"><div class="px-3 pb-2 pt-1 space-y-2"><input type="text" placeholder="Buscar..." oninput="filtraFlyout(this)" class="w-full px-3 py-2 rounded-lg bg-white/10 text-sm text-white placeholder-gray-400 outline-none border border-white/10 focus:border-cyan-400"><label class="flex items-center gap-2 text-xs text-gray-300 px-1 cursor-pointer select-none"><input type="checkbox" onchange="filtraFlyout(this)" class="rounded text-cyan-500"> Somente Emissão</label></div>
                        <div class="px-4 pt-3 pb-1 text-[11px] tracking-[0.2em] text-gray-500 font-semibold">—&nbsp; MATRÍCULA ONLINE</div>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium">Processos</p>
                        <a href="{{ Route::has('matricula-online.aberturas.index') ? route('matricula-online.aberturas.index') : route('matricula-online.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">140</span> Abertura de Matrícula Online</a>
                        <a href="{{ Route::has('matricula-online.inscricoes.index') ? route('matricula-online.inscricoes.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">149</span> Acompanhamento de Inscrições</a>
                        <a href="{{ Route::has('matricula-online.cupons.index') ? route('matricula-online.cupons.index') : '#' }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">182</span> Cadastro de Cupons de Desconto</a>
                        <a href="{{ route('matricula-online.cupons-personalizados.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('matricula-online.cupons-personalizados.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">193</span> Cadastro de Cupons Personalizados</a>
                        <a href="{{ route('cadastros.index', 'tags-matricula-online') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">74</span> Cadastro de Tag Matrícula Online</a>
                        <a href="{{ route('matricula-online.emissao-inscricoes') }}" target="_blank" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">187</span> Emissão de Inscrições</a>
                        <a href="{{ route('matricula-online.painel.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10 {{ request()->routeIs('matricula-online.painel.*') ? 'text-primary-600' : '' }}"><span class="text-gray-500 mr-1">151</span> Painel de Inscrições Online</a>
                    </div>
                </div>

                {{-- PORTAIS --}}
                <div>
                    <button @click="openMod = openMod===11 ? null : 11" class="w-[calc(100%-16px)] mx-2 my-0.5 rounded-xl flex flex-col items-center justify-center gap-1.5 py-2.5 text-[10px] leading-tight transition-colors hover:bg-white/10 {{ request()->is('portais*') ? 'bg-gray-200 text-gray-900' : 'text-gray-400' }}" :class="openMod===11 ? 'bg-gray-200 text-gray-900' : ''"><i class="fa-solid fa-desktop text-lg"></i><span class="text-center px-0.5">Portais</span></button>
                    <div x-show="openMod===11" x-cloak class="fixed left-28 top-0 z-50 w-80 h-screen bg-[#2a2f36] shadow-2xl border border-black/30 overflow-y-auto py-2"><div class="px-3 pb-2 pt-1 space-y-2"><input type="text" placeholder="Buscar..." oninput="filtraFlyout(this)" class="w-full px-3 py-2 rounded-lg bg-white/10 text-sm text-white placeholder-gray-400 outline-none border border-white/10 focus:border-cyan-400"><label class="flex items-center gap-2 text-xs text-gray-300 px-1 cursor-pointer select-none"><input type="checkbox" onchange="filtraFlyout(this)" class="rounded text-cyan-500"> Somente Emissão</label></div>
                        <div class="px-4 pt-3 pb-1 text-[11px] tracking-[0.2em] text-gray-500 font-semibold">—&nbsp; PORTAIS</div>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium">Configuração</p>
                        <a href="#" class="submenu-item block text-gray-500 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">189</span> Aprovação de Fotos do Aluno</a>
                        <a href="{{ route('portais.configuracao') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">46</span> Configuração (Portal Aluno)</a>
                        <a href="{{ route('portais.config-inscricao.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">92</span> Configuração (Portal de Inscrição)</a>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Feedbacks</p>
                        <a href="{{ route('geral.questionarios.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">228</span> Questionário Avulso</a>
                        <a href="#" class="submenu-item block text-gray-500 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">69</span> Resultados Feedback - Professores</a>
                        <p class="px-4 py-2.5 text-[13px] text-gray-300 font-medium mt-1">Publicação (Portal do Aluno)</p>
                        <a href="{{ route('cadastros.index', 'eventos-portal') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">240</span> Cadastro de Eventos (Portal Aluno)</a>
                        <a href="{{ route('portais.pastas.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">76</span> Cadastro de Pastas (Portal Aluno)</a>
                        <a href="{{ route('portais.publicacoes.index') }}" class="submenu-item block text-gray-300 hover:text-white hover:bg-white/10"><span class="text-gray-500 mr-1">77</span> Publicações (Portal Aluno)</a>
                    </div>
                </div>
            </nav>

            {{-- Ajuda --}}
            <div class="border-t border-white/10 p-2">
                <a href="{{ Route::has('tickets.index') ? route('tickets.index') : '#' }}" class="flex flex-col items-center justify-center gap-1 py-2 text-[9px] leading-tight text-white bg-cyan-500 hover:bg-cyan-400 rounded-xl"><i class="fa-solid fa-circle-question text-lg"></i><span>Ajuda</span></a>
            </div>
        </aside>

        <div x-show="openMod !== null" x-cloak @click="openMod = null" class="fixed inset-0 z-30"></div>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 ml-28 min-w-0 overflow-x-hidden">

            {{-- TOPBAR (fiel ao EDUQ: abas DENTRO da faixa cinza + botão "+" cyan) --}}
            <header class="sticky top-0 z-30 bg-gray-100 border-b border-gray-200 h-14 flex items-center justify-between px-3 gap-3">
                <div class="flex items-center gap-1 min-w-0 flex-1 overflow-x-auto scrollbar-thin" x-data="tabBar()" x-init="init()">
                    {{-- Aba Início (fixa) --}}
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm shrink-0 {{ request()->routeIs('dashboard') ? 'bg-white shadow-sm text-gray-800 font-medium' : 'text-gray-500 hover:bg-white/70' }}">
                        <i class="fa-solid fa-house text-xs"></i> Início
                    </a>
                    {{-- Abas dinâmicas (funções abertas) --}}
                    <template x-for="tab in tabs" :key="tab.url">
                        <div class="flex items-center rounded-md shrink-0 max-w-[200px]" :class="tab.url === current ? 'bg-white shadow-sm' : 'hover:bg-white/70'">
                            <a :href="tab.url" class="pl-3 pr-1 py-1.5 text-sm truncate" :class="tab.url === current ? 'text-gray-800 font-medium' : 'text-gray-500'" x-text="tab.title"></a>
                            <button @click.prevent="close(tab.url)" class="px-1.5 py-1 text-gray-400 hover:text-red-500" title="Fechar"><i class="fa-solid fa-xmark text-xs"></i></button>
                        </div>
                    </template>
                    {{-- Botao "+" cyan: abre nova função (assinatura do EDUQ) --}}
                    <button @click="searchOpen = !searchOpen" class="w-7 h-7 shrink-0 flex items-center justify-center rounded-md bg-cyan-100 hover:bg-cyan-200 text-cyan-600 ml-1" title="Abrir nova função (Ctrl+K)">
                        <i class="fa-solid fa-plus text-xs"></i>
                    </button>
                </div>

                <div class="flex items-center gap-0.5 shrink-0">
                    <a href="{{ route('dashboard') }}" class="hidden md:block p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-200/60 rounded-lg" title="Início">
                        <i class="fa-solid fa-house"></i>
                    </a>
                    <a href="#" class="hidden md:block p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-200/60 rounded-lg" title="Novidades">
                        <i class="fa-solid fa-gift"></i>
                    </a>
                    <a href="{{ Route::has('notificacoes.index') ? route('notificacoes.index') : '#' }}" class="p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-200/60 rounded-lg relative" title="Notificações">
                        <i class="fa-regular fa-bell"></i>
                        @php $notifCount = \App\Models\Notificacao::where('user_id', Auth::id())->where('lida', false)->count() ?? 0; @endphp
                        @if($notifCount > 0)
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                        @endif
                    </a>
                    <a href="{{ Route::has('agenda.index') ? route('agenda.index') : '#' }}" class="hidden md:block p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-200/60 rounded-lg relative" title="Agenda">
                        <i class="fa-regular fa-calendar"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                    </a>
                    <a href="{{ Route::has('tickets.index') ? route('tickets.index') : '#' }}" class="hidden md:block p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-200/60 rounded-lg" title="Tickets">
                        <i class="fa-solid fa-ticket-simple"></i>
                    </a>
                    <a href="{{ Route::has('tickets.create') ? route('tickets.create') : '#' }}" class="hidden md:block p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-200/60 rounded-lg" title="Ajuda">
                        <i class="fa-regular fa-circle-question"></i>
                    </a>
                    <a href="#" class="hidden md:block p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-200/60 rounded-lg" title="Reportar problema">
                        <i class="fa-solid fa-bug"></i>
                    </a>
                    <a href="{{ Route::has('configuracoes.index') ? route('configuracoes.index') : '#' }}" class="hidden md:block p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-200/60 rounded-lg" title="Configurações">
                        <i class="fa-solid fa-gear"></i>
                    </a>
                    <button @click="dark = !dark; localStorage.setItem('braseducrm_dark', dark)" class="hidden md:block p-2 text-amber-400 hover:text-amber-500 hover:bg-gray-200/60 rounded-lg" title="Alternar tema">
                        <i class="fa-solid" :class="dark ? 'fa-moon' : 'fa-sun'"></i>
                    </button>
                    {{-- Botao Painel (EDUQ) --}}
                    <a href="{{ route('painel-cliente.index') }}" class="ml-1 hidden md:flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-primary-600 text-sm font-medium">
                        <i class="fa-solid fa-table-cells-large text-xs"></i> Painel
                    </a>

                    {{-- User Menu (chip com tenant, estilo EDUQ) --}}
                    <div class="relative ml-1" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 pl-1 pr-2 py-1 rounded-xl bg-white border border-gray-200 hover:bg-gray-50">
                            <div class="w-8 h-8 bg-cyan-100 rounded-full flex items-center justify-center text-cyan-600 text-xs font-bold">
                                {{ strtoupper(substr(Auth::user()->nome, 0, 2)) }}
                            </div>
                            <div class="text-left hidden lg:block leading-tight">
                                <div class="text-xs font-semibold text-gray-700 uppercase truncate max-w-[140px]">{{ Auth::user()->nome }}</div>
                                <div class="text-[10px] text-gray-400">apresentacao</div>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 hidden lg:block"></i>
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

            {{-- Título da página p/ o tabBar --}}
            @php $pageTitle = trim($__env->yieldContent('title')); @endphp
            <script>window.__PAGE_TITLE__ = @json($pageTitle !== '' ? $pageTitle : 'Início');</script>

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
            <main class="p-4 bg-white min-h-[calc(100vh-3.5rem)] @yield('mainbg')">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // ===== Flyout estilo EDUQ: categorias em acordeão (fechadas) + filtro Buscar/Somente Emissão =====
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('aside .fixed.overflow-y-auto').forEach(function (fly) {
                if (fly.dataset.acc) return;
                fly.dataset.acc = '1';
                fly.querySelectorAll('p').forEach(function (h) {
                    // agrupa os links seguintes até a próxima categoria
                    var wrap = document.createElement('div');
                    wrap.className = 'hidden';
                    var n = h.nextElementSibling;
                    while (n && n.tagName === 'A') { var nx = n.nextElementSibling; wrap.appendChild(n); n = nx; }
                    h.after(wrap);
                    // vira linha clicável com chevron (EDUQ)
                    h.classList.add('cursor-pointer', 'flex', 'items-center', 'justify-between', 'hover:bg-white/10', 'rounded', 'select-none', 'mx-1');
                    var ch = document.createElement('i');
                    ch.className = 'fa-solid fa-chevron-right text-[9px] mr-2 transition-transform';
                    h.appendChild(ch);
                    h.addEventListener('click', function () {
                        wrap.classList.toggle('hidden');
                        ch.classList.toggle('rotate-90');
                    });
                });
            });
        });

        // Filtro do flyout (Buscar + Somente Emissão): expande e filtra; limpo = volta ao acordeão fechado
        window.filtraFlyout = function (el) {
            var fly = el.closest('.overflow-y-auto');
            var q = (fly.querySelector('input[type=text]').value || '').toLowerCase();
            var so = fly.querySelector('input[type=checkbox]').checked;
            var filtering = q.length > 0 || so;
            fly.querySelectorAll('p').forEach(function (p) {
                var wrap = p.nextElementSibling;
                if (!wrap || wrap.tagName === 'A') return;
                var vis = 0;
                wrap.querySelectorAll('a').forEach(function (a) {
                    var t = a.textContent.toLowerCase();
                    var ok = (!q || t.indexOf(q) !== -1) && (!so || t.indexOf('emiss') !== -1);
                    a.style.display = ok ? '' : 'none';
                    if (ok) vis++;
                });
                var chev = p.querySelector('i.fa-chevron-right');
                if (filtering) {
                    wrap.classList.toggle('hidden', vis === 0);
                    p.style.display = vis ? '' : 'none';
                    if (chev) chev.classList.add('rotate-90');
                } else {
                    wrap.classList.add('hidden');
                    p.style.display = '';
                    if (chev) chev.classList.remove('rotate-90');
                }
            });
        };

        function tabBar() {
            return {
                tabs: [],
                current: window.location.pathname,
                init() {
                    try { this.tabs = JSON.parse(localStorage.getItem('braseducrm_tabs') || '[]'); } catch (e) { this.tabs = []; }
                    const url = window.location.pathname;
                    if (url === '/login' || url === '/' || url === '/dashboard') return;
                    const title = (window.__PAGE_TITLE__ || document.title || url).toString();
                    const existing = this.tabs.find(t => t.url === url);
                    if (existing) { existing.title = title; }
                    else { this.tabs.push({ url, title }); }
                    if (this.tabs.length > 12) { this.tabs = this.tabs.slice(-12); }
                    this.save();
                },
                close(url) {
                    this.tabs = this.tabs.filter(t => t.url !== url);
                    this.save();
                    if (url === this.current) {
                        const last = this.tabs[this.tabs.length - 1];
                        window.location.href = last ? last.url : '/dashboard';
                    }
                },
                closeAll() { this.tabs = []; this.save(); window.location.href = '/dashboard'; },
                save() { localStorage.setItem('braseducrm_tabs', JSON.stringify(this.tabs)); },
            };
        }
    </script>
    @stack('scripts')
    <script>
        // Labels flutuantes estilo Material (EDUQ) - transforma pares label+campo
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('form').forEach(function (f) {
                if (f.closest('header') || f.closest('aside')) return;
                f.querySelectorAll('label').forEach(function (lb) {
                    var el = lb.nextElementSibling;
                    if (!el) return;
                    var tag = el.tagName;
                    if (tag !== 'INPUT' && tag !== 'SELECT' && tag !== 'TEXTAREA') return;
                    if (tag === 'INPUT' && ['checkbox', 'radio', 'file', 'hidden', 'submit', 'button'].indexOf(el.type) !== -1) return;
                    if (lb.querySelector('input,select,textarea,button')) return;
                    var wrap = document.createElement('div');
                    wrap.className = 'fl-wrap' + (tag === 'TEXTAREA' ? ' fl-area' : '');
                    lb.parentNode.insertBefore(wrap, lb);
                    wrap.appendChild(el);
                    wrap.appendChild(lb);
                    lb.classList.add('fl-label');
                    lb.classList.remove('block', 'mb-1', 'mb-2');
                    if ((tag === 'INPUT' || tag === 'TEXTAREA') && !el.placeholder) el.placeholder = ' ';
                    function upd() {
                        var has;
                        if (tag === 'SELECT') has = true;
                        else if (tag === 'INPUT' && ['date', 'time', 'datetime-local', 'month', 'week'].indexOf(el.type) !== -1) has = true;
                        else has = !!el.value || document.activeElement === el || (el.placeholder && el.placeholder.trim() !== '');
                        wrap.classList.toggle('fl-float', has);
                    }
                    ['focus', 'blur', 'input', 'change'].forEach(function (ev) { el.addEventListener(ev, upd); });
                    upd();
                });
            });
        });
    </script>
</body>
</html>
