@extends('layouts.app')
@section('title', 'Notificacoes')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-bell text-primary-500 text-xl"></i>
            <h1 class="text-2xl font-bold text-gray-800">Notificacoes</h1>
            @if($totalNaoLidas > 0)
            <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $totalNaoLidas }}</span>
            @endif
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('notificacoes.index') }}"
               class="px-3 py-1.5 text-sm rounded-lg border {{ !request('filtro') ? 'bg-primary-50 text-primary-600 border-primary-200' : 'text-gray-600 hover:bg-gray-50' }}">
                Todas
            </a>
            <a href="{{ route('notificacoes.index', ['filtro' => 'nao_lidas']) }}"
               class="px-3 py-1.5 text-sm rounded-lg border {{ request('filtro') === 'nao_lidas' ? 'bg-primary-50 text-primary-600 border-primary-200' : 'text-gray-600 hover:bg-gray-50' }}">
                Nao lidas
            </a>
            <a href="{{ route('notificacoes.index', ['filtro' => 'lidas']) }}"
               class="px-3 py-1.5 text-sm rounded-lg border {{ request('filtro') === 'lidas' ? 'bg-primary-50 text-primary-600 border-primary-200' : 'text-gray-600 hover:bg-gray-50' }}">
                Lidas
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border divide-y">
        @forelse($notificacoes as $notificacao)
        <div class="p-4 flex items-start gap-4 {{ !$notificacao->lida ? 'bg-blue-50/50' : '' }} hover:bg-gray-50 transition">
            {{-- Icon --}}
            <div class="flex-shrink-0 mt-0.5">
                @switch($notificacao->tipo)
                    @case('success')
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-check-circle text-green-600"></i>
                        </div>
                        @break
                    @case('warning')
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-exclamation-triangle text-yellow-600"></i>
                        </div>
                        @break
                    @case('error')
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-times-circle text-red-600"></i>
                        </div>
                        @break
                    @default
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-info-circle text-blue-600"></i>
                        </div>
                @endswitch
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 {{ !$notificacao->lida ? 'font-bold' : '' }}">
                            {{ $notificacao->titulo }}
                        </h3>
                        @if($notificacao->mensagem)
                        <p class="text-sm text-gray-600 mt-0.5">{{ $notificacao->mensagem }}</p>
                        @endif
                        <p class="text-xs text-gray-400 mt-1">
                            <i class="fa-regular fa-clock mr-1"></i>{{ $notificacao->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        @if(!$notificacao->lida)
                        <form method="POST" action="{{ route('notificacoes.marcar-lida', $notificacao) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-xs text-primary-600 hover:text-primary-800 px-2 py-1 rounded hover:bg-primary-50 transition" title="Marcar como lida">
                                <i class="fa-solid fa-check mr-1"></i>Marcar lida
                            </button>
                        </form>
                        @else
                        <span class="text-xs text-gray-400"><i class="fa-solid fa-check-double"></i></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-bell-slash text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-gray-600 font-medium">Nenhuma notificacao</h3>
            <p class="text-sm text-gray-400 mt-1">Voce nao possui notificacoes {{ request('filtro') === 'nao_lidas' ? 'nao lidas' : (request('filtro') === 'lidas' ? 'lidas' : '') }}.</p>
        </div>
        @endforelse
    </div>

    @if($notificacoes->hasPages())
    <div class="mt-4">
        {{ $notificacoes->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
