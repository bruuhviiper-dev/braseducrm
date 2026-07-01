@extends('layouts.app')
@section('title', 'Aviso de Pagamento')

@section('content')
<div class="bg-white rounded-xl border">
    <div class="p-5 border-b flex items-center gap-3">
        <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">234</span>
        <h1 class="text-lg font-semibold text-gray-800">Aviso de Pagamento (confirmações)</h1>
    </div>
    @if(session('success'))<div class="mx-5 mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-2 rounded text-sm">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="mx-5 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded text-sm">{{ session('error') }}</div>@endif
    <div class="p-4">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Pessoa</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Documento</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Pago em</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Valor</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Enviar confirmação</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($titulos as $t)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $t->pessoa?->nome ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $t->numero_documento ?? $t->id }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ optional($t->data_pagamento)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-gray-800">R$ {{ number_format($t->valor_pago ?? $t->valor_original, 2, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('comunicacao.mensagens.enviar-aviso-pagamento', $t) }}" class="flex items-center gap-2">
                            @csrf
                            <select name="canal" class="border rounded px-2 py-1 text-xs">
                                <option value="email">E-mail</option>
                                <option value="sms">SMS</option>
                                <option value="whatsapp">WhatsApp</option>
                            </select>
                            <button class="px-2.5 py-1 bg-primary-600 text-white rounded text-xs hover:bg-primary-700"><i class="fa-solid fa-paper-plane"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhum pagamento recebido.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $titulos->links() }}</div>
    </div>
</div>
@endsection
