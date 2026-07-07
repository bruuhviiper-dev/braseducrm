@extends('layouts.app')
@section('title', isset($ticket) ? 'Editar Ticket' : 'Novo Ticket')

@section('content')
<div class="w-full">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('tickets.index') }}" class="text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <i class="fa-solid fa-ticket text-primary-500 text-xl"></i>
        <h1 class="text-2xl font-bold text-gray-800">{{ isset($ticket) ? 'Editar Ticket #'.$ticket->id : 'Novo Ticket' }}</h1>
    </div>

    <div class="bg-white rounded-xl border p-6">
        <form method="POST" action="{{ isset($ticket) ? route('tickets.update', $ticket) : route('tickets.store') }}">
            @csrf
            @if(isset($ticket))
                @method('PUT')
            @endif

            <div class="mb-4">
                <label for="assunto" class="block text-sm font-medium text-gray-700 mb-1">Assunto</label>
                <input type="text" name="assunto" id="assunto" value="{{ old('assunto', $ticket->assunto ?? '') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none text-sm"
                       placeholder="Descreva brevemente o problema...">
                @error('assunto')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="prioridade" class="block text-sm font-medium text-gray-700 mb-1">Prioridade</label>
                <select name="prioridade" id="prioridade"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none text-sm">
                    <option value="baixa" {{ old('prioridade', $ticket->prioridade ?? '') === 'baixa' ? 'selected' : '' }}>Baixa</option>
                    <option value="media" {{ old('prioridade', $ticket->prioridade ?? 'media') === 'media' ? 'selected' : '' }}>Media</option>
                    <option value="alta" {{ old('prioridade', $ticket->prioridade ?? '') === 'alta' ? 'selected' : '' }}>Alta</option>
                    <option value="urgente" {{ old('prioridade', $ticket->prioridade ?? '') === 'urgente' ? 'selected' : '' }}>Urgente</option>
                </select>
                @error('prioridade')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">Descricao</label>
                <textarea name="descricao" id="descricao" rows="6"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none text-sm"
                          placeholder="Descreva o problema em detalhes...">{{ old('descricao', $ticket->descricao ?? '') }}</textarea>
                @error('descricao')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-primary-700 transition">
                    <i class="fa-solid fa-paper-plane mr-1"></i> {{ isset($ticket) ? 'Atualizar' : 'Enviar Ticket' }}
                </button>
                <a href="{{ route('tickets.index') }}" class="text-gray-500 hover:text-gray-700 text-sm">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
