@extends('layouts.app')
@section('title', 'Exportação de Oportunidades')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">159</span>
            <h1 class="text-lg font-semibold text-gray-800">Exportação de Oportunidades</h1>
        </div>
        <div class="p-6 space-y-4">
            <p class="text-sm text-gray-600">Total de oportunidades disponíveis: <strong>{{ $total }}</strong>. Gere um arquivo CSV (compatível com Excel) com todas as oportunidades.</p>
            <form method="GET" action="{{ route('crm.exportacao.csv') }}" class="flex gap-3 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Situação</label>
                    <select name="situacao" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Todas</option>
                        @foreach(['aberta','ganha','perdida','pausada'] as $s)<option value="{{ $s }}">{{ ucfirst($s) }}</option>@endforeach
                    </select>
                </div>
                <button class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700"><i class="fa-solid fa-file-csv mr-1"></i> Exportar CSV</button>
            </form>
        </div>
    </div>
</div>
@endsection
