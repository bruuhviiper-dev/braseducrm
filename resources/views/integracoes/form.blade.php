@extends('layouts.app')
@section('title', 'Configurar ' . $definicao['nome'])

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <div class="flex items-center gap-3">
                <i class="fa-{{ $chave === 'whatsapp' ? 'brands' : 'solid' }} {{ $definicao['icone'] }} text-lg text-primary-500"></i>
                <h2 class="text-base font-semibold text-gray-800">{{ $definicao['nome'] }}</h2>
            </div>
            <a href="{{ route('integracoes.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ route('integracoes.update', $chave) }}" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <p class="text-sm text-gray-500">{{ $definicao['descricao'] }}</p>

            @foreach($definicao['campos'] as $campo => $label)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                <input type="text" name="cred_{{ $campo }}" value="{{ $integracao->credenciais[$campo] ?? '' }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" autocomplete="off">
            </div>
            @endforeach

            <div class="flex items-center gap-2 border-t pt-4">
                <input type="checkbox" name="ativo" value="1" id="ativo" {{ $integracao->ativo ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="ativo" class="text-sm text-gray-700">Integração ativa</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30">Salvar Configuração</button>
                <a href="{{ route('integracoes.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
