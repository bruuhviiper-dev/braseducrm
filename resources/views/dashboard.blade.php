@extends('layouts.app')
@section('mainbg', '!bg-gray-100')
@section('title', 'Dashboard')

@section('content')
{{-- Banner Painel do Cliente --}}
<div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 flex items-center justify-between gap-3">
    <div class="flex items-center gap-3 min-w-0">
        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
            <i class="fa-solid fa-display text-blue-600"></i>
        </div>
        <div class="min-w-0">
            <h3 class="font-semibold text-gray-800">Conheça o Painel do Cliente</h3>
            <p class="text-sm text-gray-600 truncate">Centralize integrações, comunicação e configurações da sua instituição em um só lugar.</p>
        </div>
    </div>
    <a href="{{ route('painel-cliente.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition whitespace-nowrap shrink-0">
        Acessar Painel <i class="fa-solid fa-arrow-right ml-1"></i>
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- FAVORITOS --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-9 h-9 bg-yellow-50 rounded-lg flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-star text-yellow-400"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-800 leading-tight">Favoritos</h2>
                    <p class="text-xs text-gray-400">Suas funções favoritas. Arraste para reposicionar.</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                @forelse($favoritos as $fav)
                <a href="#" class="flex items-center gap-2 px-3 py-2 border rounded-lg hover:bg-gray-50 transition text-xs group max-w-[220px]">
                    <span class="text-gray-300 group-hover:text-gray-400"><i class="fa-solid fa-grip-vertical"></i></span>
                    <span class="font-bold text-gray-700">{{ $fav->codigo }}</span>
                    <span class="text-gray-600 truncate">{{ $fav->nome }}</span>
                </a>
                @empty
                <div class="w-full text-center text-gray-400 py-4">
                    <i class="fa-regular fa-star text-2xl mb-2"></i>
                    <p class="text-sm">Adicione funções aos favoritos para acesso rápido</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- MINHAS ATIVIDADES --}}
    <div class="bg-white rounded-xl border p-5">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-list-check text-blue-500"></i>
                </div>
                <h2 class="text-base font-bold text-gray-800">Minhas atividades</h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="#" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 border rounded-lg px-3 py-1">
                    <i class="fa-solid fa-eye text-xs"></i> Ver todas
                </a>
                <button class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrows-rotate"></i></button>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-3 mb-4">
            <div class="bg-white border rounded-xl p-4 text-center">
                <div class="flex items-center justify-center mb-1">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-clock text-blue-600 text-sm"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500">A vencer hoje</p>
                <p class="text-2xl font-bold text-gray-800">{{ $atividadesHoje }}</p>
            </div>
            <div class="bg-white border rounded-xl p-4 text-center">
                <div class="flex items-center justify-center mb-1">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-exclamation text-red-600 text-sm"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500">Atrasado</p>
                <p class="text-2xl font-bold text-red-600">{{ $atividadesAtrasadas }}</p>
            </div>
            <div class="bg-white border rounded-xl p-4 text-center">
                <div class="flex items-center justify-center mb-1">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-calendar-day text-purple-600 text-sm"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500">Futuro</p>
                <p class="text-2xl font-bold text-gray-800">{{ $atividadesFuturas }}</p>
            </div>
        </div>

        @php
            $total = $atividadesHoje + $atividadesAtrasadas + $atividadesFuturas;
            $pctAtrasado = $total > 0 ? ($atividadesAtrasadas / $total) * 100 : 0;
            $pctFuturo = $total > 0 ? ($atividadesFuturas / $total) * 100 : 0;
        @endphp
        <div class="w-full h-1.5 bg-gray-100 rounded-full flex overflow-hidden mb-4">
            <div class="bg-red-500 h-full" style="width: {{ $pctAtrasado }}%"></div>
            <div class="bg-purple-500 h-full" style="width: {{ $pctFuturo }}%"></div>
        </div>

        <p class="text-sm text-gray-500 mb-3">Hoje (Vencidas e a vencer)</p>
        <div class="space-y-2">
            @forelse($atividadesRecentes as $ativ)
            <div class="flex items-center justify-between p-2.5 border rounded-lg hover:bg-gray-50">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-7 h-7 bg-gray-50 border rounded-md flex items-center justify-center shrink-0">
                        <i class="fa-regular fa-calendar text-gray-400 text-xs"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $ativ->titulo }}</p>
                </div>
                @if($ativ->data_vencimento && $ativ->data_vencimento < now())
                <span class="text-xs text-red-500 flex items-center gap-1 shrink-0"><i class="fa-regular fa-clock"></i> {{ $ativ->data_vencimento->diffForHumans() }}</span>
                @else
                <span class="text-xs text-gray-400 flex items-center gap-1 shrink-0"><i class="fa-regular fa-clock"></i> Em instantes</span>
                @endif
            </div>
            @empty
            <div class="text-center py-6 text-gray-400">
                <i class="fa-regular fa-calendar-check text-3xl mb-2"></i>
                <p class="text-sm">Nenhuma atividade pendente</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- RECENTES + NOVIDADES --}}
    <div class="space-y-6">
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-9 h-9 bg-emerald-50 rounded-lg flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-clock-rotate-left text-emerald-500"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-800 leading-tight">Recentes</h2>
                    <p class="text-xs text-gray-400">Funções acessadas recentemente</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                @forelse($recentes as $rec)
                <a href="#" class="flex items-center gap-2 p-2.5 border rounded-lg hover:bg-gray-50 text-xs">
                    <span class="font-bold text-gray-700 min-w-[28px]">{{ $rec->codigo }}</span>
                    <span class="text-gray-600 truncate">{{ $rec->nome }}</span>
                </a>
                @empty
                <div class="col-span-2 text-center py-6 text-gray-400">
                    <i class="fa-regular fa-clock text-3xl mb-2"></i>
                    <p class="text-sm">Nenhuma função recém acessada.</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-9 h-9 bg-orange-50 rounded-lg flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-gift text-orange-400"></i>
                </div>
                <h2 class="text-base font-bold text-gray-800">Novidades</h2>
            </div>
            <div class="space-y-2">
                @foreach([
                    ['tag' => 'MELHORIA', 'titulo' => 'Integração com Receita Federal — Validação de documentos e situação cadastral', 'data' => '17/jun'],
                    ['tag' => 'MELHORIA', 'titulo' => 'Mais inteligência para acompanhar suas campanhas: nova gestão do Meta Pixel no Portal de Inscrições', 'data' => '11/jun'],
                    ['tag' => 'MELHORIA', 'titulo' => 'Personalize a apresentação das disciplinas no histórico do aluno', 'data' => '10/jun'],
                    ['tag' => 'LANÇAMENTO', 'titulo' => 'Novo Painel Acadêmico com totalizadores e filtro de período', 'data' => '02/jun'],
                ] as $nov)
                <div class="border rounded-lg p-3 hover:bg-gray-50">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[10px] font-bold tracking-wide px-1.5 py-0.5 rounded {{ $nov['tag'] === 'MELHORIA' ? 'bg-cyan-50 text-cyan-600' : 'bg-emerald-50 text-emerald-600' }}">{{ $nov['tag'] }}</span>
                        <span class="text-[11px] text-gray-400">{{ $nov['data'] }}</span>
                    </div>
                    <p class="text-sm text-gray-700">{{ $nov['titulo'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
