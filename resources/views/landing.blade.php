<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="One - Sistema de Gestao Academica Completo para Instituicoes de Ensino. CRM, Academico, Financeiro, EAD e muito mais.">
    <title>One - Gestao Academica Completa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                            950: '#1e1b4b',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }

        .gradient-hero {
            background-color: #312e81;
            background-image: linear-gradient(135deg, #1e1b4b 0%, #312e81 25%, #3730a3 50%, #4338ca 75%, #4f46e5 100%);
        }

        /* Pontos sobrepostos AO gradiente (camadas no mesmo background-image) */
        .hero-pattern {
            background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.06) 1px, transparent 1px),
                              radial-gradient(circle at 75% 75%, rgba(255,255,255,0.06) 1px, transparent 1px),
                              linear-gradient(135deg, #1e1b4b 0%, #312e81 25%, #3730a3 50%, #4338ca 75%, #4f46e5 100%);
            background-size: 50px 50px, 50px 50px, 100% 100%;
            background-color: #312e81;
        }

        .glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }

        .pricing-popular {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
        }

        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .mock-screen {
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .float-animation {
            animation: float 4s ease-in-out infinite;
        }

        @keyframes countUp {
            from { opacity: 0; transform: scale(0.5); }
            to { opacity: 1; transform: scale(1); }
        }

        .scroll-indicator {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
    </style>
</head>
<body class="bg-white text-gray-800 antialiased" x-data="{ mobileMenu: false }">

    {{-- ===== HEADER / NAV ===== --}}
    <header x-data="{ scrolled: false }"
            x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })"
            :class="scrolled ? 'bg-white/90 backdrop-blur-lg shadow-md' : 'bg-transparent'"
            class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                {{-- Logo --}}
                <a href="#" class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-lg bg-brand-600 flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-bold" :class="scrolled ? 'text-gray-900' : 'text-white'">
                        One
                    </span>
                </a>

                {{-- Desktop Nav --}}
                <nav class="hidden lg:flex items-center gap-8">
                    <a href="#funcionalidades" :class="scrolled ? 'text-gray-700 hover:text-brand-600' : 'text-white/90 hover:text-white'" class="text-sm font-medium transition-colors">Funcionalidades</a>
                    <a href="#modulos" :class="scrolled ? 'text-gray-700 hover:text-brand-600' : 'text-white/90 hover:text-white'" class="text-sm font-medium transition-colors">Modulos</a>
                    <a href="#integracoes" :class="scrolled ? 'text-gray-700 hover:text-brand-600' : 'text-white/90 hover:text-white'" class="text-sm font-medium transition-colors">Integracoes</a>
                    <a href="#precos" :class="scrolled ? 'text-gray-700 hover:text-brand-600' : 'text-white/90 hover:text-white'" class="text-sm font-medium transition-colors">Precos</a>
                    <a href="#contato" :class="scrolled ? 'text-gray-700 hover:text-brand-600' : 'text-white/90 hover:text-white'" class="text-sm font-medium transition-colors">Contato</a>
                    <a href="{{ route('login') }}" class="ml-4 inline-flex items-center px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-lg shadow-brand-600/25">
                        <i class="fas fa-sign-in-alt mr-2"></i>Acessar Sistema
                    </a>
                </nav>

                {{-- Mobile Menu Button --}}
                <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2 rounded-lg" :class="scrolled ? 'text-gray-700' : 'text-white'">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenu" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="lg:hidden bg-white shadow-xl border-t">
            <div class="px-4 py-4 space-y-3">
                <a href="#funcionalidades" @click="mobileMenu = false" class="block text-gray-700 hover:text-brand-600 font-medium py-2">Funcionalidades</a>
                <a href="#modulos" @click="mobileMenu = false" class="block text-gray-700 hover:text-brand-600 font-medium py-2">Modulos</a>
                <a href="#precos" @click="mobileMenu = false" class="block text-gray-700 hover:text-brand-600 font-medium py-2">Precos</a>
                <a href="#contato" @click="mobileMenu = false" class="block text-gray-700 hover:text-brand-600 font-medium py-2">Contato</a>
                <a href="{{ route('login') }}" class="block w-full text-center px-5 py-3 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-lg transition-colors">
                    <i class="fas fa-sign-in-alt mr-2"></i>Acessar Sistema
                </a>
            </div>
        </div>
    </header>

    {{-- ===== HERO SECTION ===== --}}
    <section class="gradient-hero hero-pattern relative overflow-hidden min-h-screen flex items-center">
        {{-- Decorative circles --}}
        <div class="absolute top-20 left-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-indigo-400/10 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 lg:py-40 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                {{-- Left content --}}
                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20 text-white/90 text-sm mb-6">
                        <i class="fas fa-rocket text-blue-400"></i>
                        <span>Plataforma #1 em Gestao Educacional</span>
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6">
                        Gestao Academica Completa para sua
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300">Instituicao</span>
                    </h1>
                    <p class="text-lg sm:text-xl text-blue-100/80 mb-10 max-w-xl leading-relaxed">
                        CRM, Academico, Financeiro, EAD e muito mais em uma unica plataforma. Simplifique a gestao da sua instituicao de ensino.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#contato" class="inline-flex items-center justify-center px-8 py-4 bg-white text-brand-700 font-bold rounded-xl hover:bg-blue-50 transition-all shadow-xl shadow-black/20 text-base">
                            <i class="fas fa-calendar-check mr-2"></i>Solicitar Demonstracao
                        </a>
                        <a href="#funcionalidades" class="inline-flex items-center justify-center px-8 py-4 bg-white/10 border border-white/25 text-white font-semibold rounded-xl hover:bg-white/20 transition-all text-base">
                            <i class="fas fa-th-large mr-2"></i>Conhecer Funcionalidades
                        </a>
                    </div>
                </div>

                {{-- Right: Mock dashboard --}}
                <div class="hidden lg:block float-animation">
                    <div class="glass rounded-2xl p-6 shadow-2xl">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            <span class="text-white/50 text-xs ml-2">One - Dashboard</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-white/10 rounded-lg p-4">
                                <div class="text-blue-300 text-xs font-medium mb-1">Alunos Ativos</div>
                                <div class="text-white text-2xl font-bold">1.247</div>
                                <div class="text-green-400 text-xs mt-1"><i class="fas fa-arrow-up"></i> +12%</div>
                            </div>
                            <div class="bg-white/10 rounded-lg p-4">
                                <div class="text-blue-300 text-xs font-medium mb-1">Receita Mensal</div>
                                <div class="text-white text-2xl font-bold">R$ 89k</div>
                                <div class="text-green-400 text-xs mt-1"><i class="fas fa-arrow-up"></i> +8%</div>
                            </div>
                            <div class="bg-white/10 rounded-lg p-4">
                                <div class="text-blue-300 text-xs font-medium mb-1">Leads CRM</div>
                                <div class="text-white text-2xl font-bold">342</div>
                                <div class="text-green-400 text-xs mt-1"><i class="fas fa-arrow-up"></i> +24%</div>
                            </div>
                            <div class="bg-white/10 rounded-lg p-4">
                                <div class="text-blue-300 text-xs font-medium mb-1">Turmas Ativas</div>
                                <div class="text-white text-2xl font-bold">56</div>
                                <div class="text-blue-300 text-xs mt-1"><i class="fas fa-check"></i> Em dia</div>
                            </div>
                        </div>
                        <div class="bg-white/10 rounded-lg p-4">
                            <div class="text-blue-300 text-xs font-medium mb-3">Funil de Vendas</div>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 rounded-full bg-blue-400" style="width:100%"></div>
                                    <span class="text-white/70 text-xs whitespace-nowrap">Contato 128</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="h-2 rounded-full bg-indigo-400" style="width:72%"></div>
                                    <span class="text-white/70 text-xs whitespace-nowrap">Visita 92</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="h-2 rounded-full bg-purple-400" style="width:45%"></div>
                                    <span class="text-white/70 text-xs whitespace-nowrap">Proposta 58</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="h-2 rounded-full bg-green-400" style="width:28%"></div>
                                    <span class="text-white/70 text-xs whitespace-nowrap">Matricula 36</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scroll indicator --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 scroll-indicator">
            <a href="#stats" class="text-white/50 hover:text-white/80 transition-colors">
                <i class="fas fa-chevron-down text-2xl"></i>
            </a>
        </div>
    </section>

    {{-- ===== STATS BAR ===== --}}
    <section id="stats" class="relative -mt-1 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div x-data="{ shown: false }" x-init="
                const observer = new IntersectionObserver(entries => {
                    entries.forEach(entry => { if (entry.isIntersecting) shown = true; });
                }, { threshold: 0.3 });
                observer.observe($el);
            " class="relative -mt-16 z-20 bg-white rounded-2xl shadow-xl border border-gray-100 p-8 grid grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center" x-show="shown" x-transition.duration.500ms>
                    <div class="text-3xl sm:text-4xl font-extrabold text-brand-600">200+</div>
                    <div class="text-gray-500 text-sm mt-1">Funcionalidades</div>
                </div>
                <div class="text-center" x-show="shown" x-transition.duration.700ms>
                    <div class="text-3xl sm:text-4xl font-extrabold text-brand-600">13</div>
                    <div class="text-gray-500 text-sm mt-1">Modulos</div>
                </div>
                <div class="text-center" x-show="shown" x-transition.duration.900ms>
                    <div class="text-3xl sm:text-4xl font-extrabold text-brand-600">100%</div>
                    <div class="text-gray-500 text-sm mt-1">Brasileiro</div>
                </div>
                <div class="text-center" x-show="shown" x-transition.duration.1100ms>
                    <div class="text-3xl sm:text-4xl font-extrabold text-brand-600"><i class="fas fa-headset text-2xl sm:text-3xl"></i></div>
                    <div class="text-gray-500 text-sm mt-1">Suporte Dedicado</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== COMPARATIVO (ANTES x DEPOIS) ===== --}}
    <section id="comparativo" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-brand-50 text-brand-600 text-sm font-semibold rounded-full mb-4">Por que mudar</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">Troque vários sistemas por um só</h2>
                <p class="text-gray-500 text-lg max-w-2xl mx-auto">Chega de planilhas soltas e ferramentas que não conversam entre si. Centralize tudo em uma única plataforma.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                {{-- Sem o sistema --}}
                <div class="rounded-2xl border border-red-100 bg-red-50/40 p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-11 h-11 rounded-xl bg-red-100 flex items-center justify-center">
                            <i class="fas fa-xmark text-red-500 text-lg"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Sem o One</h3>
                    </div>
                    <ul class="space-y-4">
                        @foreach([
                            'Dados espalhados em planilhas e sistemas diferentes',
                            'Retrabalho ao digitar a mesma informação várias vezes',
                            'Cobrança manual e alto índice de inadimplência',
                            'Captação de alunos sem controle ou acompanhamento',
                            'Relatórios demorados, feitos à mão',
                        ] as $item)
                        <li class="flex items-start gap-3 text-sm text-gray-600">
                            <i class="fas fa-circle-xmark text-red-400 mt-0.5"></i>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Com o sistema --}}
                <div class="rounded-2xl border-2 border-green-200 bg-green-50/40 p-8 shadow-lg shadow-green-600/5">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-11 h-11 rounded-xl bg-green-100 flex items-center justify-center">
                            <i class="fas fa-check text-green-600 text-lg"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Com o One</h3>
                    </div>
                    <ul class="space-y-4">
                        @foreach([
                            'Tudo integrado: acadêmico, financeiro, CRM e comunicação',
                            'A informação é cadastrada uma vez e flui por todo o sistema',
                            'Boletos, cartão e cobrança automática reduzindo a inadimplência',
                            'Funil de vendas para captar e converter mais matrículas',
                            'Painéis e relatórios em tempo real para decidir na hora',
                        ] as $item)
                        <li class="flex items-start gap-3 text-sm text-gray-700">
                            <i class="fas fa-circle-check text-green-500 mt-0.5"></i>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== FUNCIONALIDADES (FEATURES) ===== --}}
    <section id="funcionalidades" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" x-data="{ shown: false }" x-init="
                const observer = new IntersectionObserver(entries => {
                    entries.forEach(entry => { if (entry.isIntersecting) shown = true; });
                }, { threshold: 0.3 });
                observer.observe($el);
            ">
                <div x-show="shown" x-transition.duration.500ms>
                    <span class="inline-block px-4 py-1.5 bg-brand-50 text-brand-600 text-sm font-semibold rounded-full mb-4">Funcionalidades</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">Tudo que sua instituicao precisa</h2>
                    <p class="text-gray-500 text-lg max-w-2xl mx-auto">Uma plataforma completa com todas as ferramentas para gerenciar sua instituicao de ensino de forma eficiente.</p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                $features = [
                    ['icon' => 'fa-funnel-dollar', 'color' => 'blue', 'title' => 'CRM Educacional', 'desc' => 'Funil de vendas, captacao de alunos, gestao de oportunidades e acompanhamento completo do processo comercial.'],
                    ['icon' => 'fa-university', 'color' => 'indigo', 'title' => 'Gestao Academica', 'desc' => 'Cursos, matrizes curriculares, turmas, matriculas, lancamento de notas e controle de frequencia.'],
                    ['icon' => 'fa-file-invoice-dollar', 'color' => 'green', 'title' => 'Financeiro Completo', 'desc' => 'Titulos a receber e pagar, boletos, fluxo de caixa, DRE, renegociacoes e plano de contas.'],
                    ['icon' => 'fa-comments', 'color' => 'purple', 'title' => 'Comunicacao Integrada', 'desc' => 'WhatsApp, SMS, Email com templates automatizados para comunicacao eficiente com alunos e responsaveis.'],
                    ['icon' => 'fa-laptop', 'color' => 'cyan', 'title' => 'EAD e Matricula Online', 'desc' => 'Cursos a distancia, inscricao online, portal do aluno e gestao completa do ensino remoto.'],
                    ['icon' => 'fa-chart-bar', 'color' => 'orange', 'title' => 'Relatorios e Paineis', 'desc' => 'Dashboards em tempo real, relatorios dinamicos e indicadores de desempenho para tomada de decisao.'],
                ];
                $colorMap = [
                    'blue' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
                    'indigo' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-600'],
                    'green' => ['bg' => 'bg-green-100', 'text' => 'text-green-600'],
                    'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600'],
                    'cyan' => ['bg' => 'bg-cyan-100', 'text' => 'text-cyan-600'],
                    'orange' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-600'],
                ];
                @endphp

                @foreach($features as $i => $feature)
                <div x-data="{ shown: false }" x-init="
                    const observer = new IntersectionObserver(entries => {
                        entries.forEach(entry => { if (entry.isIntersecting) shown = true; });
                    }, { threshold: 0.2 });
                    observer.observe($el);
                ">
                    <div x-show="shown" x-transition.duration.500ms
                         class="card-hover bg-white border border-gray-100 rounded-2xl p-8 shadow-sm hover:shadow-xl">
                        <div class="w-14 h-14 rounded-xl {{ $colorMap[$feature['color']]['bg'] }} flex items-center justify-center mb-5">
                            <i class="fas {{ $feature['icon'] }} text-xl {{ $colorMap[$feature['color']]['text'] }}"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $feature['title'] }}</h3>
                        <p class="text-gray-500 leading-relaxed">{{ $feature['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== MODULOS ===== --}}
    <section id="modulos" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-brand-50 text-brand-600 text-sm font-semibold rounded-full mb-4">Modulos</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">13 Modulos Integrados</h2>
                <p class="text-gray-500 text-lg max-w-2xl mx-auto">Todos os modulos funcionam de forma integrada, compartilhando dados e automatizando processos.</p>
            </div>

            @php
            $modulos = [
                ['icon' => 'fa-tachometer-alt', 'name' => 'Dashboard', 'desc' => 'Visao geral com indicadores e atalhos rapidos'],
                ['icon' => 'fa-graduation-cap', 'name' => 'Academico', 'desc' => 'Cursos, turmas, matrizes e gestao academica'],
                ['icon' => 'fa-building', 'name' => 'Administrativo', 'desc' => 'Pessoas, alunos e gestao administrativa'],
                ['icon' => 'fa-envelope', 'name' => 'Comunicacao', 'desc' => 'Templates, mensagens e canais de contato'],
                ['icon' => 'fa-boxes', 'name' => 'Estoque', 'desc' => 'Produtos, materiais e controle de inventario'],
                ['icon' => 'fa-funnel-dollar', 'name' => 'CRM', 'desc' => 'Funil de vendas, leads e oportunidades'],
                ['icon' => 'fa-laptop', 'name' => 'EAD', 'desc' => 'Ensino a distancia e cursos online'],
                ['icon' => 'fa-wallet', 'name' => 'Financeiro', 'desc' => 'Contas, titulos, fluxo de caixa e DRE'],
                ['icon' => 'fa-folder-open', 'name' => 'GED', 'desc' => 'Gestao eletronica de documentos'],
                ['icon' => 'fa-cogs', 'name' => 'Geral', 'desc' => 'Configuracoes e parametros do sistema'],
                ['icon' => 'fa-plug', 'name' => 'Integracoes', 'desc' => 'APIs, webhooks e integracoes externas'],
                ['icon' => 'fa-user-plus', 'name' => 'Matricula Online', 'desc' => 'Inscricao e matricula pelo portal web'],
                ['icon' => 'fa-globe', 'name' => 'Portais', 'desc' => 'Portal do aluno, professor e responsavel'],
            ];
            @endphp

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @foreach($modulos as $mod)
                <div x-data="{ shown: false }" x-init="
                    const observer = new IntersectionObserver(entries => {
                        entries.forEach(entry => { if (entry.isIntersecting) shown = true; });
                    }, { threshold: 0.2 });
                    observer.observe($el);
                ">
                    <div x-show="shown" x-transition.duration.500ms
                         class="card-hover bg-white border border-gray-100 rounded-xl p-5 text-center shadow-sm hover:shadow-lg hover:border-brand-200 group">
                        <div class="w-12 h-12 rounded-lg bg-brand-50 group-hover:bg-brand-100 flex items-center justify-center mx-auto mb-3 transition-colors">
                            <i class="fas {{ $mod['icon'] }} text-brand-600 text-lg"></i>
                        </div>
                        <h4 class="font-bold text-gray-900 text-sm mb-1">{{ $mod['name'] }}</h4>
                        <p class="text-gray-400 text-xs leading-relaxed">{{ $mod['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== SCREENSHOTS / PREVIEW ===== --}}
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-brand-50 text-brand-600 text-sm font-semibold rounded-full mb-4">Preview</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">Interface Moderna e Intuitiva</h2>
                <p class="text-gray-500 text-lg max-w-2xl mx-auto">Conheca a interface pensada para facilitar o dia a dia da sua instituicao.</p>
            </div>

            <div x-data="{ activeTab: 'dashboard' }">
                {{-- Tabs --}}
                <div class="flex justify-center mb-10">
                    <div class="inline-flex bg-gray-100 rounded-xl p-1.5 gap-1">
                        <button @click="activeTab = 'dashboard'" :class="activeTab === 'dashboard' ? 'bg-white shadow-md text-brand-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2.5 rounded-lg text-sm font-semibold transition-all">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </button>
                        <button @click="activeTab = 'crm'" :class="activeTab === 'crm' ? 'bg-white shadow-md text-brand-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2.5 rounded-lg text-sm font-semibold transition-all">
                            <i class="fas fa-funnel-dollar mr-2"></i>CRM Funil
                        </button>
                        <button @click="activeTab = 'financeiro'" :class="activeTab === 'financeiro' ? 'bg-white shadow-md text-brand-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2.5 rounded-lg text-sm font-semibold transition-all">
                            <i class="fas fa-wallet mr-2"></i>Financeiro
                        </button>
                    </div>
                </div>

                {{-- Tab: Dashboard --}}
                <div x-show="activeTab === 'dashboard'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="mock-screen p-6 shadow-xl rounded-2xl max-w-5xl mx-auto">
                        <div class="flex items-center gap-2 mb-6 pb-4 border-b border-gray-200">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            <span class="text-gray-400 text-xs ml-3">One / Dashboard</span>
                        </div>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-gray-400 text-xs">Alunos</span>
                                    <i class="fas fa-users text-blue-400 text-sm"></i>
                                </div>
                                <div class="text-2xl font-bold text-gray-800">1.247</div>
                                <div class="text-green-500 text-xs mt-1">+12% este mes</div>
                            </div>
                            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-gray-400 text-xs">Receita</span>
                                    <i class="fas fa-dollar-sign text-green-400 text-sm"></i>
                                </div>
                                <div class="text-2xl font-bold text-gray-800">R$ 189k</div>
                                <div class="text-green-500 text-xs mt-1">+8% este mes</div>
                            </div>
                            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-gray-400 text-xs">Turmas</span>
                                    <i class="fas fa-chalkboard text-purple-400 text-sm"></i>
                                </div>
                                <div class="text-2xl font-bold text-gray-800">56</div>
                                <div class="text-blue-500 text-xs mt-1">4 novas turmas</div>
                            </div>
                            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-gray-400 text-xs">Leads</span>
                                    <i class="fas fa-user-plus text-orange-400 text-sm"></i>
                                </div>
                                <div class="text-2xl font-bold text-gray-800">342</div>
                                <div class="text-green-500 text-xs mt-1">+24% este mes</div>
                            </div>
                        </div>
                        <div class="grid lg:grid-cols-2 gap-4">
                            <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
                                <h4 class="text-sm font-semibold text-gray-700 mb-4"><i class="fas fa-star text-yellow-400 mr-2"></i>Favoritos</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50">
                                        <div class="w-8 h-8 rounded bg-blue-100 flex items-center justify-center"><i class="fas fa-users text-blue-500 text-xs"></i></div>
                                        <span class="text-sm text-gray-600">Listar Alunos</span>
                                    </div>
                                    <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50">
                                        <div class="w-8 h-8 rounded bg-green-100 flex items-center justify-center"><i class="fas fa-file-invoice text-green-500 text-xs"></i></div>
                                        <span class="text-sm text-gray-600">Titulos a Receber</span>
                                    </div>
                                    <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50">
                                        <div class="w-8 h-8 rounded bg-purple-100 flex items-center justify-center"><i class="fas fa-funnel-dollar text-purple-500 text-xs"></i></div>
                                        <span class="text-sm text-gray-600">Funil de Vendas</span>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
                                <h4 class="text-sm font-semibold text-gray-700 mb-4"><i class="fas fa-clock text-blue-400 mr-2"></i>Atividades Recentes</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3 text-sm">
                                        <div class="w-2 h-2 rounded-full bg-green-400"></div>
                                        <span class="text-gray-600">Nova matricula - Maria Silva</span>
                                        <span class="text-gray-300 ml-auto text-xs">2min</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-sm">
                                        <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                                        <span class="text-gray-600">Lead convertido - Carlos Souza</span>
                                        <span class="text-gray-300 ml-auto text-xs">15min</span>
                                    </div>
                                    <div class="flex items-center gap-3 text-sm">
                                        <div class="w-2 h-2 rounded-full bg-orange-400"></div>
                                        <span class="text-gray-600">Titulo pago - R$ 1.200,00</span>
                                        <span class="text-gray-300 ml-auto text-xs">1h</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tab: CRM Funil --}}
                <div x-show="activeTab === 'crm'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="mock-screen p-6 shadow-xl rounded-2xl max-w-5xl mx-auto">
                        <div class="flex items-center gap-2 mb-6 pb-4 border-b border-gray-200">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            <span class="text-gray-400 text-xs ml-3">One / CRM / Funil de Vendas</span>
                        </div>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                            @php
                            $columns = [
                                ['title' => 'Contato Inicial', 'color' => 'blue', 'count' => 12, 'items' => [
                                    ['name' => 'Ana Paula Mendes', 'course' => 'Administracao', 'value' => 'R$ 800/mes'],
                                    ['name' => 'Roberto Lima', 'course' => 'Direito', 'value' => 'R$ 1.200/mes'],
                                    ['name' => 'Julia Santos', 'course' => 'Pedagogia', 'value' => 'R$ 600/mes'],
                                ]],
                                ['title' => 'Visita Agendada', 'color' => 'yellow', 'count' => 8, 'items' => [
                                    ['name' => 'Carlos Ferreira', 'course' => 'Engenharia', 'value' => 'R$ 1.500/mes'],
                                    ['name' => 'Mariana Costa', 'course' => 'Medicina', 'value' => 'R$ 3.200/mes'],
                                ]],
                                ['title' => 'Proposta Enviada', 'color' => 'purple', 'count' => 5, 'items' => [
                                    ['name' => 'Fernando Alves', 'course' => 'MBA Gestao', 'value' => 'R$ 980/mes'],
                                    ['name' => 'Patricia Rocha', 'course' => 'Pos Graduacao', 'value' => 'R$ 1.100/mes'],
                                ]],
                                ['title' => 'Matriculado', 'color' => 'green', 'count' => 3, 'items' => [
                                    ['name' => 'Lucas Oliveira', 'course' => 'Ciencia da Comp.', 'value' => 'R$ 1.400/mes'],
                                ]],
                            ];
                            $colorMap2 = ['blue' => 'bg-blue-500', 'yellow' => 'bg-yellow-500', 'purple' => 'bg-purple-500', 'green' => 'bg-green-500'];
                            $colorMapLight = ['blue' => 'bg-blue-50', 'yellow' => 'bg-yellow-50', 'purple' => 'bg-purple-50', 'green' => 'bg-green-50'];
                            @endphp

                            @foreach($columns as $col)
                            <div class="{{ $colorMapLight[$col['color']] }} rounded-xl p-3">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-xs font-bold text-gray-700">{{ $col['title'] }}</h4>
                                    <span class="text-xs bg-white rounded-full px-2 py-0.5 text-gray-500 font-medium">{{ $col['count'] }}</span>
                                </div>
                                <div class="space-y-2">
                                    @foreach($col['items'] as $item)
                                    <div class="bg-white rounded-lg p-3 shadow-sm border border-gray-100">
                                        <div class="text-sm font-semibold text-gray-800 mb-1">{{ $item['name'] }}</div>
                                        <div class="text-xs text-gray-400">{{ $item['course'] }}</div>
                                        <div class="text-xs font-bold text-brand-600 mt-1">{{ $item['value'] }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Tab: Financeiro --}}
                <div x-show="activeTab === 'financeiro'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="mock-screen p-6 shadow-xl rounded-2xl max-w-5xl mx-auto">
                        <div class="flex items-center gap-2 mb-6 pb-4 border-b border-gray-200">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            <span class="text-gray-400 text-xs ml-3">One / Financeiro</span>
                        </div>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                            <div class="bg-green-50 border border-green-100 rounded-xl p-4">
                                <div class="text-green-600 text-xs font-medium mb-1">Receitas</div>
                                <div class="text-xl font-bold text-gray-800">R$ 245.800</div>
                                <div class="text-green-500 text-xs mt-1"><i class="fas fa-arrow-up"></i> +15%</div>
                            </div>
                            <div class="bg-red-50 border border-red-100 rounded-xl p-4">
                                <div class="text-red-600 text-xs font-medium mb-1">Despesas</div>
                                <div class="text-xl font-bold text-gray-800">R$ 89.340</div>
                                <div class="text-red-500 text-xs mt-1"><i class="fas fa-arrow-down"></i> -3%</div>
                            </div>
                            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                                <div class="text-blue-600 text-xs font-medium mb-1">Saldo</div>
                                <div class="text-xl font-bold text-gray-800">R$ 156.460</div>
                                <div class="text-green-500 text-xs mt-1"><i class="fas fa-check-circle"></i> Positivo</div>
                            </div>
                            <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4">
                                <div class="text-yellow-600 text-xs font-medium mb-1">Inadimplencia</div>
                                <div class="text-xl font-bold text-gray-800">4,2%</div>
                                <div class="text-green-500 text-xs mt-1"><i class="fas fa-arrow-down"></i> -1.8%</div>
                            </div>
                        </div>
                        <div class="grid lg:grid-cols-2 gap-4">
                            <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
                                <h4 class="text-sm font-semibold text-gray-700 mb-4">Titulos a Receber (Proximos)</h4>
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                                        <span class="text-sm text-gray-600">Maria Silva</span>
                                        <span class="text-sm font-semibold text-gray-800">R$ 1.200,00</span>
                                        <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full">Vence 05/02</span>
                                    </div>
                                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                                        <span class="text-sm text-gray-600">Joao Santos</span>
                                        <span class="text-sm font-semibold text-gray-800">R$ 980,00</span>
                                        <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">Vence 10/02</span>
                                    </div>
                                    <div class="flex items-center justify-between py-2">
                                        <span class="text-sm text-gray-600">Ana Costa</span>
                                        <span class="text-sm font-semibold text-gray-800">R$ 1.500,00</span>
                                        <span class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded-full">Vencido</span>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
                                <h4 class="text-sm font-semibold text-gray-700 mb-4">Fluxo de Caixa - Ultimos 6 meses</h4>
                                <div class="space-y-3">
                                    @php
                                    $months = [
                                        ['name' => 'Jan', 'in' => 85, 'out' => 35],
                                        ['name' => 'Fev', 'in' => 78, 'out' => 40],
                                        ['name' => 'Mar', 'in' => 92, 'out' => 38],
                                        ['name' => 'Abr', 'in' => 88, 'out' => 42],
                                        ['name' => 'Mai', 'in' => 95, 'out' => 36],
                                        ['name' => 'Jun', 'in' => 100, 'out' => 33],
                                    ];
                                    @endphp
                                    @foreach($months as $month)
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs text-gray-400 w-8">{{ $month['name'] }}</span>
                                        <div class="flex-1 flex items-center gap-1">
                                            <div class="h-3 rounded-full bg-green-400" style="width:{{ $month['in'] }}%"></div>
                                            <div class="h-3 rounded-full bg-red-300" style="width:{{ $month['out'] }}%"></div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <div class="flex items-center gap-4 mt-2 text-xs text-gray-400">
                                        <span><span class="inline-block w-3 h-3 rounded-full bg-green-400 mr-1"></span>Receitas</span>
                                        <span><span class="inline-block w-3 h-3 rounded-full bg-red-300 mr-1"></span>Despesas</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== INTEGRACOES ===== --}}
    <section id="integracoes" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-brand-50 text-brand-600 text-sm font-semibold rounded-full mb-4">Integrações</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">Conectado com as ferramentas que você já usa</h2>
                <p class="text-gray-500 text-lg max-w-2xl mx-auto">Um ecossistema integrado para automatizar cobranças, marketing e comunicação.</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 max-w-5xl mx-auto">
                @php
                $integracoes = [
                    ['icon' => 'fa-bullseye', 'name' => 'RD Station', 'desc' => 'Leads e marketing'],
                    ['icon' => 'fa-barcode', 'name' => 'Boletos CNAB', 'desc' => 'Remessa e retorno'],
                    ['icon' => 'fa-credit-card', 'name' => 'Cartão', 'desc' => 'Gateway de pagamento'],
                    ['icon' => 'fa-whatsapp', 'name' => 'WhatsApp', 'desc' => 'Mensagens', 'brand' => true],
                    ['icon' => 'fa-comment-sms', 'name' => 'SMS', 'desc' => 'Avisos e campanhas'],
                    ['icon' => 'fa-file-invoice', 'name' => 'NF-e', 'desc' => 'Notas fiscais'],
                ];
                @endphp

                @foreach($integracoes as $int)
                <div class="card-hover bg-white border border-gray-100 rounded-xl p-5 text-center shadow-sm hover:shadow-lg">
                    <div class="w-12 h-12 rounded-lg bg-brand-50 flex items-center justify-center mx-auto mb-3">
                        <i class="fa-{{ ($int['brand'] ?? false) ? 'brands' : 'solid' }} {{ $int['icon'] }} text-brand-600 text-xl"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 text-sm">{{ $int['name'] }}</h4>
                    <p class="text-gray-400 text-xs mt-1">{{ $int['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== PRICING ===== --}}
    <section id="precos" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-brand-50 text-brand-600 text-sm font-semibold rounded-full mb-4">Precos</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">Planos que cabem no seu orcamento</h2>
                <p class="text-gray-500 text-lg max-w-2xl mx-auto">Escolha o plano ideal para o tamanho da sua instituicao.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                {{-- Basico --}}
                <div class="card-hover bg-white rounded-2xl border border-gray-200 p-8 flex flex-col">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Basico</h3>
                        <p class="text-gray-400 text-sm mt-1">Para instituicoes em crescimento</p>
                    </div>
                    <div class="mb-6">
                        <span class="text-4xl font-extrabold text-gray-900">R$ 497</span>
                        <span class="text-gray-400">/mes</span>
                    </div>
                    <ul class="space-y-3 mb-8 flex-1">
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>Ate 200 alunos
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>3 operadores
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>Modulo Academico
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>Modulo Administrativo
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>Modulo Financeiro
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>Suporte por email
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-300">
                            <i class="fas fa-times"></i>CRM e EAD
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-300">
                            <i class="fas fa-times"></i>Integracao WhatsApp
                        </li>
                    </ul>
                    <a href="#contato" class="block w-full text-center px-6 py-3 border-2 border-brand-600 text-brand-600 font-semibold rounded-xl hover:bg-brand-50 transition-colors">
                        Solicitar Demonstracao
                    </a>
                </div>

                {{-- Profissional (Popular) --}}
                <div class="card-hover relative bg-white rounded-2xl border-2 border-brand-600 p-8 flex flex-col shadow-xl shadow-brand-600/10 scale-105">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                        <span class="px-4 py-1.5 bg-brand-600 text-white text-xs font-bold rounded-full uppercase tracking-wide">Mais Popular</span>
                    </div>
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Profissional</h3>
                        <p class="text-gray-400 text-sm mt-1">Para instituicoes consolidadas</p>
                    </div>
                    <div class="mb-6">
                        <span class="text-4xl font-extrabold text-gray-900">R$ 997</span>
                        <span class="text-gray-400">/mes</span>
                    </div>
                    <ul class="space-y-3 mb-8 flex-1">
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>Ate 1.000 alunos
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>10 operadores
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>Todos os modulos
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>CRM + EAD + Matricula Online
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>Suporte prioritario
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>Integracao WhatsApp
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>Relatorios avancados
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>Portal do aluno
                        </li>
                    </ul>
                    <a href="#contato" class="block w-full text-center px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl transition-colors shadow-lg shadow-brand-600/25">
                        Solicitar Demonstracao
                    </a>
                </div>

                {{-- Enterprise --}}
                <div class="card-hover bg-white rounded-2xl border border-gray-200 p-8 flex flex-col">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Enterprise</h3>
                        <p class="text-gray-400 text-sm mt-1">Para grandes instituicoes</p>
                    </div>
                    <div class="mb-6">
                        <span class="text-4xl font-extrabold text-gray-900">Sob consulta</span>
                    </div>
                    <ul class="space-y-3 mb-8 flex-1">
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>Alunos ilimitados
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>Operadores ilimitados
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>Todos os modulos
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>Customizacoes sob medida
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>Suporte dedicado
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>Treinamento incluso
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>SLA garantido
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <i class="fas fa-check text-green-500"></i>Servidor dedicado
                        </li>
                    </ul>
                    <a href="#contato" class="block w-full text-center px-6 py-3 border-2 border-brand-600 text-brand-600 font-semibold rounded-xl hover:bg-brand-50 transition-colors">
                        Falar com Consultor
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== TESTIMONIALS ===== --}}
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-brand-50 text-brand-600 text-sm font-semibold rounded-full mb-4">Depoimentos</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">Instituicoes que confiam no One</h2>
                <p class="text-gray-500 text-lg max-w-2xl mx-auto">Veja o que nossos clientes falam sobre a plataforma.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @php
                $testimonials = [
                    [
                        'quote' => 'O One transformou completamente a gestao da nossa faculdade. Reduzimos em 60% o tempo gasto com processos administrativos e aumentamos em 35% a captacao de novos alunos com o CRM.',
                        'name' => 'Prof. Dr. Ricardo Mendes',
                        'role' => 'Diretor Academico',
                        'institution' => 'Faculdade Horizonte - SP',
                        'stars' => 5,
                    ],
                    [
                        'quote' => 'A integracao entre o financeiro e o academico e impressionante. Antes usavamos 3 sistemas diferentes, agora tudo esta em um so lugar. O suporte e excelente e sempre nos atende rapidamente.',
                        'name' => 'Fernanda Oliveira',
                        'role' => 'Coordenadora Administrativa',
                        'institution' => 'Colegio Nova Era - RJ',
                        'stars' => 5,
                    ],
                    [
                        'quote' => 'Implementamos o modulo EAD durante a pandemia e foi essencial para manter nossas operacoes. O portal do aluno e a matricula online facilitaram muito a vida dos nossos estudantes.',
                        'name' => 'Marcos Antonio Silva',
                        'role' => 'Gestor de TI',
                        'institution' => 'Instituto Saber Mais - MG',
                        'stars' => 5,
                    ],
                ];
                @endphp

                @foreach($testimonials as $test)
                <div x-data="{ shown: false }" x-init="
                    const observer = new IntersectionObserver(entries => {
                        entries.forEach(entry => { if (entry.isIntersecting) shown = true; });
                    }, { threshold: 0.2 });
                    observer.observe($el);
                ">
                    <div x-show="shown" x-transition.duration.500ms
                         class="card-hover bg-white border border-gray-100 rounded-2xl p-8 shadow-sm hover:shadow-xl h-full flex flex-col">
                        <div class="flex gap-1 mb-4">
                            @for($i = 0; $i < $test['stars']; $i++)
                                <i class="fas fa-star text-yellow-400"></i>
                            @endfor
                        </div>
                        <blockquote class="text-gray-600 leading-relaxed mb-6 flex-1">
                            "{{ $test['quote'] }}"
                        </blockquote>
                        <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                            <div class="w-11 h-11 rounded-full bg-brand-100 flex items-center justify-center">
                                <i class="fas fa-user text-brand-600"></i>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900 text-sm">{{ $test['name'] }}</div>
                                <div class="text-gray-400 text-xs">{{ $test['role'] }} - {{ $test['institution'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== CONTACT / CTA ===== --}}
    <section id="contato" class="py-24 gradient-hero hero-pattern relative">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                {{-- Left: CTA text --}}
                <div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-6">Pronto para transformar sua gestao academica?</h2>
                    <p class="text-blue-100/80 text-lg mb-8 leading-relaxed">Preencha o formulario e um de nossos consultores entrara em contato para agendar uma demonstracao personalizada do One.</p>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 text-white/90">
                            <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center">
                                <i class="fas fa-check text-green-400"></i>
                            </div>
                            <span>Demonstracao gratuita e sem compromisso</span>
                        </div>
                        <div class="flex items-center gap-3 text-white/90">
                            <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center">
                                <i class="fas fa-check text-green-400"></i>
                            </div>
                            <span>Implantacao assistida por especialistas</span>
                        </div>
                        <div class="flex items-center gap-3 text-white/90">
                            <div class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center">
                                <i class="fas fa-check text-green-400"></i>
                            </div>
                            <span>Suporte tecnico durante toda a migracao</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Contact form --}}
                <div class="bg-white rounded-2xl p-8 shadow-2xl">
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm flex items-center gap-3">
                            <i class="fas fa-check-circle text-green-500 text-lg"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <h3 class="text-xl font-bold text-gray-900 mb-6">Solicitar Demonstracao</h3>
                    <form action="{{ route('landing.contato') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome completo *</label>
                            <input type="text" name="nome" id="nome" value="{{ old('nome') }}" required
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all text-sm"
                                   placeholder="Seu nome completo">
                            @error('nome')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all text-sm"
                                       placeholder="seu@email.com">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="telefone" class="block text-sm font-medium text-gray-700 mb-1">Telefone *</label>
                                <input type="tel" name="telefone" id="telefone" value="{{ old('telefone') }}" required
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all text-sm"
                                       placeholder="(11) 99999-9999">
                                @error('telefone')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label for="instituicao" class="block text-sm font-medium text-gray-700 mb-1">Instituicao *</label>
                            <input type="text" name="instituicao" id="instituicao" value="{{ old('instituicao') }}" required
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all text-sm"
                                   placeholder="Nome da sua instituicao">
                            @error('instituicao')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="mensagem" class="block text-sm font-medium text-gray-700 mb-1">Mensagem *</label>
                            <textarea name="mensagem" id="mensagem" rows="4" required
                                      class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 outline-none transition-all text-sm resize-none"
                                      placeholder="Conte-nos sobre sua instituicao e suas necessidades...">{{ old('mensagem') }}</textarea>
                            @error('mensagem')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="w-full px-6 py-4 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl transition-colors shadow-lg shadow-brand-600/25 text-base">
                            <i class="fas fa-paper-plane mr-2"></i>Solicitar Demonstracao
                        </button>
                        <p class="text-gray-400 text-xs text-center">Ao enviar, voce concorda com nossa politica de privacidade.</p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== FOOTER ===== --}}
    <footer class="bg-gray-900 text-gray-400 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
                {{-- Brand --}}
                <div class="lg:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-9 h-9 rounded-lg bg-brand-600 flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-white text-lg"></i>
                        </div>
                        <span class="text-xl font-bold text-white">One</span>
                    </div>
                    <p class="text-sm leading-relaxed mb-4">Plataforma completa de gestao academica para instituicoes de ensino. CRM, Academico, Financeiro, EAD e muito mais.</p>
                    <div class="flex gap-3">
                        <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-brand-600 flex items-center justify-center transition-colors"><i class="fab fa-facebook-f text-sm"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-brand-600 flex items-center justify-center transition-colors"><i class="fab fa-instagram text-sm"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-brand-600 flex items-center justify-center transition-colors"><i class="fab fa-linkedin-in text-sm"></i></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-brand-600 flex items-center justify-center transition-colors"><i class="fab fa-youtube text-sm"></i></a>
                    </div>
                </div>

                {{-- Links --}}
                <div>
                    <h4 class="text-white font-semibold mb-4">Produto</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#funcionalidades" class="hover:text-white transition-colors">Funcionalidades</a></li>
                        <li><a href="#modulos" class="hover:text-white transition-colors">Modulos</a></li>
                        <li><a href="#precos" class="hover:text-white transition-colors">Precos</a></li>
                        <li><a href="#contato" class="hover:text-white transition-colors">Demonstracao</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4">Suporte</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Central de Ajuda</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Documentacao</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Status do Sistema</a></li>
                        <li><a href="#contato" class="hover:text-white transition-colors">Fale Conosco</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4">Contato</h4>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-center gap-3">
                            <i class="fas fa-envelope text-brand-400"></i>
                            <span>contato@one.com.br</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-phone text-brand-400"></i>
                            <span>(11) 4000-1234</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fab fa-whatsapp text-brand-400"></i>
                            <span>(11) 99000-1234</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt text-brand-400 mt-0.5"></i>
                            <span>Sao Paulo - SP, Brasil</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm">&copy; {{ date('Y') }} One. Todos os direitos reservados.</p>
                <div class="flex gap-6 text-sm">
                    <a href="#" class="hover:text-white transition-colors">Termos de Uso</a>
                    <a href="#" class="hover:text-white transition-colors">Politica de Privacidade</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Scroll to top button --}}
    <div x-data="{ show: false }"
         x-init="window.addEventListener('scroll', () => { show = window.scrollY > 500 })"
         x-show="show" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-6 right-6 z-50">
        <a href="#" class="w-12 h-12 bg-brand-600 hover:bg-brand-700 text-white rounded-xl shadow-lg shadow-brand-600/25 flex items-center justify-center transition-colors">
            <i class="fas fa-arrow-up"></i>
        </a>
    </div>

</body>
</html>
