@extends('layouts.app')
@section('title', $title ?? 'Modulo')

@section('content')
<div class="bg-white rounded-xl border p-8 text-center">
    <div class="w-16 h-16 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fa-solid {{ $icon ?? 'fa-cog' }} text-2xl text-primary-500"></i>
    </div>
    <h1 class="text-xl font-semibold text-gray-800 mb-2">{{ $title ?? 'Modulo' }}</h1>
    <p class="text-gray-500 mb-6">{{ $description ?? 'Este modulo esta em desenvolvimento.' }}</p>

    @if(isset($funcoes))
    <div class="max-w-2xl mx-auto">
        <h3 class="text-sm font-semibold text-gray-600 mb-3 text-left">Funcoes disponíveis neste modulo:</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            @foreach($funcoes as $f)
            <div class="flex items-center gap-2 p-2.5 border rounded-lg text-sm text-left hover:bg-gray-50">
                <span class="font-semibold text-primary-600 min-w-[28px]">{{ $f['codigo'] }}</span>
                <span class="text-gray-700">{{ $f['nome'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
