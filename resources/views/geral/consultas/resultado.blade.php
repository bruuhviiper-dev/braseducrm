@extends('layouts.app')
@section('title', 'Resultado: ' . $consulta->nome)

@section('content')
<div class="bg-white rounded-xl border">
    <div class="p-5 border-b flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">221</span>
            <div>
                <h1 class="text-lg font-semibold text-gray-800">{{ $consulta->nome }}</h1>
                <p class="text-xs text-gray-500">{{ $cfg['label'] }} — {{ $rows->count() }} resultado(s)</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('geral.consultas.csv', $consulta) }}" class="px-3 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700"><i class="fa-solid fa-file-csv mr-1"></i> CSV</a>
            <a href="{{ route('geral.consultas.index') }}" class="px-3 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50"><i class="fa-solid fa-arrow-left mr-1"></i> Voltar</a>
        </div>
    </div>
    <div class="p-4 overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    @foreach($campos as $campo)
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">{{ $cfg['campos'][$campo] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($rows as $r)
                <tr class="hover:bg-gray-50">
                    @foreach($campos as $campo)
                    <td class="px-4 py-3 text-gray-700">{{ $r->{$campo} }}</td>
                    @endforeach
                </tr>
                @empty
                <tr><td colspan="{{ max(1, count($campos)) }}" class="px-4 py-8 text-center text-gray-400">Nenhum resultado para os critérios.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
