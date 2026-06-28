@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
{{-- Banner Painel do Cliente --}}
<div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
            <i class="fa-solid fa-display text-blue-600"></i>
        </div>
        <div>
            <h3 class="font-semibold text-gray-800">Conheca o Painel do Cliente</h3>
            <p class="text-sm text-gray-600">Centralize integracoes, comunicacao e configuracoes da sua instituicao em um so lugar.</p>
        </div>
    </div>
    <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition whitespace-nowrap">
        Acessar Painel <i class="fa-solid fa-arrow-right ml-1"></i>
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- FAVORITOS --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-4">
                <i class="fa-solid fa-star text-yellow-400"></i>
                <h2 class="text-lg font-semibold text-gray-800">Favoritos</h2>
                <span class="text-sm text-gray-500">Suas funcoes favoritas. Arraste para reposicionar.</span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                @forelse($favoritos as $fav)
                <a href="#" class="flex items-center gap-2 p-3 border rounded-lg hover:bg-gray-50 transition text-sm group">
                    <span class="text-gray-400 group-hover:text-primary-500"><i class="fa-solid fa-grip-vertical"></i></span>
                    <span class="font-semibold text-primary-600">{{ $fav->codigo }}</span>
                    <span class="text-gray-700 truncate">{{ $fav->nome }}</span>
                </a>
                @empty
                <div class="col-span-full text-center text-gray-400 py-4">
                    <i class="fa-regular fa-star text-2xl mb-2"></i>
                    <p class="text-sm">Adicione funcoes aos favoritos para acesso rapido</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- MINHAS ATIVIDADES --}}
    <div class="bg-white rounded-xl border p-5">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-list-check text-primary-500"></i>
                <h2 class="text-lg font-semibold text-gray-800">Minhas atividades</h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="#" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 border rounded-lg px-3 py-1">
                    <i class="fa-solid fa-eye text-xs"></i> Ver todas
                </a>
                <button class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrows-rotate"></i></button>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-3 mb-4">
            <div class="bg-blue-50 rounded-xl p-4 text-center">
                <div class="flex items-center justify-center gap-2 mb-1">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-clock text-blue-600 text-sm"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500">A vencer hoje</p>
                <p class="text-2xl font-bold text-gray-800">{{ $atividadesHoje }}</p>
            </div>
            <div class="bg-red-50 rounded-xl p-4 text-center">
                <div class="flex items-center justify-center gap-2 mb-1">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-exclamation text-red-600 text-sm"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500">Atrasado</p>
                <p class="text-2xl font-bold text-red-600">{{ $atividadesAtrasadas }}</p>
            </div>
            <div class="bg-purple-50 rounded-xl p-4 text-center">
                <div class="flex items-center justify-center gap-2 mb-1">
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
        <div class="w-full h-2 bg-gray-100 rounded-full flex overflow-hidden mb-4">
            <div class="bg-red-500 h-full" style="width: {{ $pctAtrasado }}%"></div>
            <div class="bg-purple-500 h-full" style="width: {{ $pctFuturo }}%"></div>
        </div>

        <p class="text-sm text-gray-500 mb-3">Hoje (Vencidas e a vencer)</p>
        <div class="space-y-2">
            @forelse($atividadesRecentes as $ativ)
            <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-circle-dot text-gray-400 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $ativ->titulo }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->nome }}</p>
                    </div>
                </div>
                @if($ativ->data_vencimento && $ativ->data_vencimento < now())
                <span class="text-xs text-red-500 flex items-center gap-1">
                    <i class="fa-solid fa-clock"></i> {{ $ativ->data_vencimento->diffForHumans() }}
                </span>
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
            <div class="flex items-center gap-2 mb-4">
                <i class="fa-solid fa-clock-rotate-left text-primary-500"></i>
                <h2 class="text-lg font-semibold text-gray-800">Recentes</h2>
                <span class="text-sm text-gray-500">Funcoes acessadas recentemente</span>
            </div>
            <div class="grid grid-cols-2 gap-2">
                @forelse($recentes as $rec)
                <a href="#" class="flex items-center gap-2 p-2.5 border rounded-lg hover:bg-gray-50 text-sm">
                    <span class="font-semibold text-primary-600 min-w-[28px]">{{ $rec->codigo }}</span>
                    <span class="text-gray-700 truncate">{{ $rec->nome }}</span>
                </a>
                @empty
                <div class="col-span-2 text-center py-4 text-gray-400 text-sm">
                    Nenhuma funcao acessada recentemente
                </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-4">
                <i class="fa-solid fa-gift text-orange-400"></i>
                <h2 class="text-lg font-semibold text-gray-800">Novidades</h2>
            </div>
            <div class="text-center py-8 text-gray-400">
                <i class="fa-regular fa-thumbs-up text-4xl mb-3"></i>
                <p class="text-sm">Nenhuma novidade para listar</p>
            </div>
        </div>
    </div>
</div>
@endsection
