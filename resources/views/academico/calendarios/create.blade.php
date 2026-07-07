@extends('layouts.app')
@section('title', 'Cadastro de Calendário')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">35</span>
            <h1 class="text-lg font-semibold text-gray-800">Cadastro de Calendário</h1>
        </div>

        <form action="{{ route('academico.calendarios.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ano <span class="text-red-500">*</span></label>
                <input type="number" name="ano" value="{{ old('ano', date('Y')) }}" min="1900" max="2100" required
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-3 py-2 rounded text-xs">
                <i class="fa-solid fa-circle-info mr-1"></i>
                Ao carregar os dias do ano, os dias úteis nascem <strong>letivos</strong> e os fins de semana e feriados nacionais nascem <strong>não-letivos</strong> (com a observação do feriado preenchida). Você ajusta tudo na tela seguinte.
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700">
                    <i class="fa-solid fa-calendar-plus mr-1"></i> Carregar dias do Ano
                </button>
                <a href="{{ route('academico.calendarios.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
