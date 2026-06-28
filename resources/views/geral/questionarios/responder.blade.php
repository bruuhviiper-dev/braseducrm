@extends('layouts.app')
@section('title', 'Responder: ' . $questionario->nome)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <div>
                <h2 class="text-base font-semibold text-gray-800">{{ $questionario->nome }}</h2>
                @if($questionario->descricao)<p class="text-xs text-gray-500 mt-1">{{ $questionario->descricao }}</p>@endif
            </div>
            <a href="{{ route('geral.questionarios.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ route('geral.questionarios.salvar-resposta', $questionario) }}" class="p-6 space-y-6">
            @csrf

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Seu nome</label>
                    <input type="text" name="respondente_nome" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                    <input type="email" name="respondente_email" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            @forelse($questionario->questoes as $i => $questao)
            <div class="border-t pt-4">
                <label class="block text-sm font-medium text-gray-800 mb-2">{{ $i + 1 }}. {{ $questao->enunciado }}</label>

                @if($questao->tipo === 'escala')
                    <div class="flex flex-wrap gap-1">
                        @for($n = 0; $n <= 10; $n++)
                        <label class="cursor-pointer">
                            <input type="radio" name="respostas[{{ $questao->id }}]" value="{{ $n }}" class="peer sr-only" required>
                            <span class="inline-flex w-9 h-9 items-center justify-center border rounded-lg text-sm peer-checked:bg-primary-600 peer-checked:text-white peer-checked:border-primary-600 hover:bg-gray-50">{{ $n }}</span>
                        </label>
                        @endfor
                    </div>
                    <div class="flex justify-between text-xs text-gray-400 mt-1"><span>Pouco provável</span><span>Muito provável</span></div>
                @elseif($questao->tipo === 'multipla_escolha')
                    <div class="space-y-1">
                        @foreach($questao->opcoes as $op)
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="radio" name="respostas[{{ $questao->id }}]" value="{{ $op->texto }}" class="text-blue-600" required> {{ $op->texto }}
                        </label>
                        @endforeach
                    </div>
                @elseif($questao->tipo === 'verdadeiro_falso')
                    <div class="flex gap-4 text-sm">
                        <label class="flex items-center gap-2"><input type="radio" name="respostas[{{ $questao->id }}]" value="Verdadeiro" class="text-blue-600" required> Verdadeiro</label>
                        <label class="flex items-center gap-2"><input type="radio" name="respostas[{{ $questao->id }}]" value="Falso" class="text-blue-600" required> Falso</label>
                    </div>
                @else
                    <textarea name="respostas[{{ $questao->id }}]" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                @endif
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-6">Este questionário não possui questões.</p>
            @endforelse

            <div class="flex gap-3 pt-2 border-t">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 mt-4">Enviar Respostas</button>
            </div>
        </form>
    </div>
</div>
@endsection
