@extends('layouts.app')
@section('title', 'Central de Tickets')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-ticket text-primary-500 text-xl"></i>
            <h1 class="text-2xl font-bold text-gray-800">Central de Tickets</h1>
        </div>
        <a href="{{ route('tickets.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-700 transition">
            <i class="fa-solid fa-plus mr-1"></i> Novo Ticket
        </a>
    </div>

    {{-- Filters --}}
    <div class="flex items-center gap-2 mb-4">
        <a href="{{ route('tickets.index') }}"
           class="px-3 py-1.5 text-sm rounded-lg border {{ !request('situacao') ? 'bg-primary-50 text-primary-600 border-primary-200' : 'text-gray-600 hover:bg-gray-50' }}">
            Todos
        </a>
        @foreach(['aberto' => 'Abertos', 'em_andamento' => 'Em andamento', 'respondido' => 'Respondidos', 'fechado' => 'Fechados'] as $key => $label)
        <a href="{{ route('tickets.index', ['situacao' => $key]) }}"
           class="px-3 py-1.5 text-sm rounded-lg border {{ request('situacao') === $key ? 'bg-primary-50 text-primary-600 border-primary-200' : 'text-gray-600 hover:bg-gray-50' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- Tickets List --}}
    <div class="bg-white rounded-xl border divide-y">
        @forelse($tickets as $ticket)
        <a href="{{ route('tickets.show', $ticket) }}" class="block p-4 hover:bg-gray-50 transition">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs text-gray-400 font-mono">#{{ $ticket->id }}</span>
                        <h3 class="text-sm font-semibold text-gray-800 truncate">{{ $ticket->assunto }}</h3>
                    </div>
                    <p class="text-sm text-gray-500 truncate">{{ Str::limit($ticket->descricao, 100) }}</p>
                    <p class="text-xs text-gray-400 mt-1">
                        <i class="fa-regular fa-clock mr-1"></i>{{ $ticket->created_at->diffForHumans() }}
                    </p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    {{-- Priority Badge --}}
                    @switch($ticket->prioridade)
                        @case('urgente')
                            <span class="px-2 py-0.5 text-xs font-medium bg-red-100 text-red-700 rounded-full">Urgente</span>
                            @break
                        @case('alta')
                            <span class="px-2 py-0.5 text-xs font-medium bg-orange-100 text-orange-700 rounded-full">Alta</span>
                            @break
                        @case('media')
                            <span class="px-2 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">Media</span>
                            @break
                        @default
                            <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">Baixa</span>
                    @endswitch

                    {{-- Status Badge --}}
                    @switch($ticket->situacao)
                        @case('aberto')
                            <span class="px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">Aberto</span>
                            @break
                        @case('em_andamento')
                            <span class="px-2 py-0.5 text-xs font-medium bg-purple-100 text-purple-700 rounded-full">Em andamento</span>
                            @break
                        @case('respondido')
                            <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded-full">Respondido</span>
                            @break
                        @case('fechado')
                            <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-500 rounded-full">Fechado</span>
                            @break
                    @endswitch
                </div>
            </div>
        </a>
        @empty
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-ticket text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-gray-600 font-medium">Nenhum ticket encontrado</h3>
            <p class="text-sm text-gray-400 mt-1">Crie um novo ticket para obter suporte.</p>
            <a href="{{ route('tickets.create') }}" class="inline-block mt-4 bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-700 transition">
                <i class="fa-solid fa-plus mr-1"></i> Novo Ticket
            </a>
        </div>
        @endforelse
    </div>

    @if($tickets->hasPages())
    <div class="mt-4">
        {{ $tickets->appends(request()->query())->links() }}
    </div>
    @endif
</div>
<x-fab :route="route('tickets.create')" />
@endsection