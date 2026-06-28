@extends('layouts.app')
@section('title', 'Matrícula Online')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">140</span>
            <h1 class="text-lg font-semibold text-gray-800">Matrícula Online</h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('matricula-online.aberturas.index') }}" class="px-3 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50"><i class="fa-solid fa-folder-open mr-1"></i> Aberturas</a>
            <a href="{{ route('matricula-online.inscricoes.index') }}" class="px-3 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50"><i class="fa-solid fa-list-check mr-1"></i> Inscrições</a>
            <a href="{{ route('matricula-online.cupons.index') }}" class="px-3 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50"><i class="fa-solid fa-ticket mr-1"></i> Cupons</a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl border p-4">
            <div class="text-2xl font-bold text-gray-800">{{ $stats['aberturas_ativas'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Aberturas ativas</div>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <div class="text-2xl font-bold text-gray-800">{{ $stats['inscricoes_total'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Inscrições totais</div>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <div class="text-2xl font-bold text-amber-600">{{ $stats['inscricoes_pendentes'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Pendentes</div>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <div class="text-2xl font-bold text-green-600">{{ $stats['inscricoes_matriculadas'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Matriculadas</div>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <div class="text-2xl font-bold text-gray-800">{{ $stats['cupons_ativos'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Cupons ativos</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Aberturas recentes --}}
        <div class="bg-white rounded-xl border">
            <div class="px-5 py-3 border-b flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-700">Aberturas recentes</h2>
                <a href="{{ route('matricula-online.aberturas.index') }}" class="text-xs text-primary-600 hover:underline">Ver todas</a>
            </div>
            <div class="divide-y">
                @forelse($aberturas as $a)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-gray-800">{{ $a->nome }}</div>
                        <div class="text-xs text-gray-400">{{ $a->data_inicio->format('d/m/Y') }} a {{ $a->data_fim->format('d/m/Y') }}</div>
                    </div>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $a->inscricoes_count }} inscrições</span>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-gray-400 text-sm">Nenhuma abertura cadastrada.</div>
                @endforelse
            </div>
        </div>

        {{-- Últimas inscrições --}}
        <div class="bg-white rounded-xl border">
            <div class="px-5 py-3 border-b flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-700">Últimas inscrições</h2>
                <a href="{{ route('matricula-online.inscricoes.index') }}" class="text-xs text-primary-600 hover:underline">Ver todas</a>
            </div>
            <div class="divide-y">
                @forelse($ultimasInscricoes as $i)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-gray-800">{{ $i->nome }}</div>
                        <div class="text-xs text-gray-400">{{ $i->abertura?->nome ?? '—' }}</div>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full capitalize bg-gray-100 text-gray-600">{{ $i->situacao }}</span>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-gray-400 text-sm">Nenhuma inscrição registrada.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
