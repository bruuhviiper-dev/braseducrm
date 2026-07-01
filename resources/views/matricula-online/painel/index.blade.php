@extends('layouts.app')
@section('title', 'Painel de Inscrições Online')

@section('content')
<div class="space-y-4">
    <div class="bg-white rounded-xl border p-5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">151</span>
            <h1 class="text-lg font-semibold text-gray-800">Painel de Inscrições Online</h1>
        </div>
        <a href="{{ route('matricula-online.emissao-inscricoes') }}" target="_blank" class="px-3 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700"><i class="fa-solid fa-file-pdf mr-1"></i> Emitir (187)</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
        <div class="bg-white rounded-xl border p-4"><p class="text-xs text-gray-500 uppercase">Total</p><p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p></div>
        <div class="bg-white rounded-xl border p-4"><p class="text-xs text-gray-500 uppercase">Pendentes</p><p class="text-2xl font-bold text-amber-600">{{ $stats['pendentes'] }}</p></div>
        <div class="bg-white rounded-xl border p-4"><p class="text-xs text-gray-500 uppercase">Aprovadas</p><p class="text-2xl font-bold text-blue-600">{{ $stats['aprovadas'] }}</p></div>
        <div class="bg-white rounded-xl border p-4"><p class="text-xs text-gray-500 uppercase">Matriculadas</p><p class="text-2xl font-bold text-green-600">{{ $stats['matriculadas'] }}</p></div>
        <div class="bg-white rounded-xl border p-4"><p class="text-xs text-gray-500 uppercase">Canceladas</p><p class="text-2xl font-bold text-red-600">{{ $stats['canceladas'] }}</p></div>
        <div class="bg-white rounded-xl border p-4"><p class="text-xs text-gray-500 uppercase">Pagas</p><p class="text-2xl font-bold text-gray-800">{{ $stats['pagas'] }}</p></div>
    </div>

    <div class="bg-white rounded-xl border">
        <div class="px-5 py-3 border-b"><h2 class="text-sm font-semibold text-gray-700">Inscrições recentes</h2></div>
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Nome</th>
                    <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Abertura</th>
                    <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Situação</th>
                    <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Pago</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($recentes as $i)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 font-medium text-gray-800">{{ $i->nome }}</td>
                    <td class="px-4 py-2 text-gray-600">{{ $i->abertura?->nome ?? '—' }}</td>
                    <td class="px-4 py-2 text-gray-600">{{ ucfirst($i->situacao) }}</td>
                    <td class="px-4 py-2">{!! $i->pagamento_confirmado ? '<span class="text-green-600"><i class="fa-solid fa-check"></i></span>' : '<span class="text-gray-300"><i class="fa-solid fa-minus"></i></span>' !!}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhuma inscrição ainda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
