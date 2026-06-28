@extends('layouts.app')
@section('title', 'Ticket #' . $ticket->id)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('tickets.index') }}" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <i class="fa-solid fa-ticket text-primary-500 text-xl"></i>
            <h1 class="text-2xl font-bold text-gray-800">Ticket #{{ $ticket->id }}</h1>

            @switch($ticket->situacao)
                @case('aberto')
                    <span class="px-2.5 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">Aberto</span>
                    @break
                @case('em_andamento')
                    <span class="px-2.5 py-1 text-xs font-medium bg-purple-100 text-purple-700 rounded-full">Em andamento</span>
                    @break
                @case('respondido')
                    <span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Respondido</span>
                    @break
                @case('fechado')
                    <span class="px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-500 rounded-full">Fechado</span>
                    @break
            @endswitch
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('tickets.edit', $ticket) }}" class="text-sm text-gray-500 hover:text-primary-600 px-3 py-1.5 border rounded-lg">
                <i class="fa-solid fa-pen text-xs mr-1"></i> Editar
            </a>
            <form method="POST" action="{{ route('tickets.destroy', $ticket) }}" onsubmit="return confirm('Tem certeza que deseja excluir este ticket?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm text-red-500 hover:text-red-700 px-3 py-1.5 border border-red-200 rounded-lg hover:bg-red-50">
                    <i class="fa-solid fa-trash text-xs mr-1"></i> Excluir
                </button>
            </form>
        </div>
    </div>

    {{-- Ticket Details --}}
    <div class="bg-white rounded-xl border p-6 mb-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">{{ $ticket->assunto }}</h2>
                <div class="flex items-center gap-3 mt-1 text-sm text-gray-500">
                    <span><i class="fa-solid fa-user mr-1"></i>{{ $ticket->user->nome }}</span>
                    <span><i class="fa-regular fa-clock mr-1"></i>{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                    @switch($ticket->prioridade)
                        @case('urgente')
                            <span class="text-red-600"><i class="fa-solid fa-flag mr-1"></i>Urgente</span>
                            @break
                        @case('alta')
                            <span class="text-orange-600"><i class="fa-solid fa-flag mr-1"></i>Alta</span>
                            @break
                        @case('media')
                            <span class="text-yellow-600"><i class="fa-solid fa-flag mr-1"></i>Media</span>
                            @break
                        @default
                            <span class="text-gray-400"><i class="fa-solid fa-flag mr-1"></i>Baixa</span>
                    @endswitch
                </div>
            </div>
        </div>
        <div class="prose prose-sm max-w-none text-gray-700 bg-gray-50 rounded-lg p-4">
            {!! nl2br(e($ticket->descricao)) !!}
        </div>
    </div>

    {{-- Messages Thread --}}
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-comments text-primary-500"></i> Mensagens
            <span class="text-sm text-gray-400 font-normal">({{ $ticket->mensagens->count() }})</span>
        </h3>

        @if($ticket->mensagens->count() > 0)
        <div class="space-y-4">
            @foreach($ticket->mensagens as $msg)
            <div class="bg-white rounded-xl border p-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                        {{ strtoupper(substr($msg->user->nome, 0, 1)) }}
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-800">{{ $msg->user->nome }}</span>
                        <span class="text-xs text-gray-400 ml-2">{{ $msg->created_at->format('d/m/Y H:i') }} - {{ $msg->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="text-sm text-gray-700 ml-11">
                    {!! nl2br(e($msg->mensagem)) !!}
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-xl border p-8 text-center">
            <p class="text-sm text-gray-400">Nenhuma mensagem ainda.</p>
        </div>
        @endif
    </div>

    {{-- Reply Form --}}
    @if($ticket->situacao !== 'fechado')
    <div class="bg-white rounded-xl border p-6">
        <h3 class="text-sm font-semibold text-gray-800 mb-3">Responder</h3>
        <form method="POST" action="{{ route('tickets.responder', $ticket) }}">
            @csrf
            <textarea name="mensagem" rows="4"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none text-sm mb-3"
                      placeholder="Digite sua resposta..."></textarea>
            @error('mensagem')
                <p class="text-red-500 text-xs mb-2">{{ $message }}</p>
            @enderror
            <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-700 transition">
                <i class="fa-solid fa-paper-plane mr-1"></i> Enviar Resposta
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
