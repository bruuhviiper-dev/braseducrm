@extends('layouts.app')
@section('title', 'Resultados: ' . $questionario->nome)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-semibold text-gray-800">Resultados — {{ $questionario->nome }}</h1>
            <p class="text-xs text-gray-500">{{ $totalRespostas }} resposta(s) recebida(s)</p>
        </div>
        <a href="{{ route('geral.questionarios.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
    </div>

    @if($nps)
    <div class="bg-white rounded-xl border p-6">
        <h2 class="text-sm font-semibold text-gray-600 mb-4">Net Promoter Score (NPS)</h2>
        <div class="flex items-center gap-8">
            <div class="text-center">
                <div class="text-5xl font-bold {{ $nps['score'] >= 50 ? 'text-green-600' : ($nps['score'] >= 0 ? 'text-amber-600' : 'text-red-600') }}">{{ number_format($nps['score'], 0) }}</div>
                <div class="text-xs text-gray-400 mt-1">Score NPS</div>
            </div>
            <div class="flex-1 space-y-2">
                <div class="flex items-center gap-2">
                    <span class="w-24 text-xs text-green-700">Promotores</span>
                    <div class="flex-1 bg-gray-100 rounded-full h-3"><div class="bg-green-500 h-3 rounded-full" style="width: {{ $nps['total'] ? ($nps['promotores']/$nps['total']*100) : 0 }}%"></div></div>
                    <span class="text-xs text-gray-600 w-8">{{ $nps['promotores'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-24 text-xs text-amber-700">Neutros</span>
                    <div class="flex-1 bg-gray-100 rounded-full h-3"><div class="bg-amber-400 h-3 rounded-full" style="width: {{ $nps['total'] ? ($nps['neutros']/$nps['total']*100) : 0 }}%"></div></div>
                    <span class="text-xs text-gray-600 w-8">{{ $nps['neutros'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-24 text-xs text-red-700">Detratores</span>
                    <div class="flex-1 bg-gray-100 rounded-full h-3"><div class="bg-red-500 h-3 rounded-full" style="width: {{ $nps['total'] ? ($nps['detratores']/$nps['total']*100) : 0 }}%"></div></div>
                    <span class="text-xs text-gray-600 w-8">{{ $nps['detratores'] }}</span>
                </div>
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-4">NPS = % Promotores (notas 9-10) − % Detratores (notas 0-6). Varia de −100 a +100.</p>
    </div>
    @else
    <div class="bg-white rounded-xl border p-8 text-center text-gray-400">
        @if($questionario->tipo === 'nps')
        Nenhuma resposta numérica registrada ainda para calcular o NPS.
        @else
        Este questionário não é do tipo NPS. Total de respostas: {{ $totalRespostas }}.
        @endif
    </div>
    @endif
</div>
@endsection
