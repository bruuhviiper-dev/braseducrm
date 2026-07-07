@extends('layouts.app')
@section('title', 'Link de Pagamento Avulso')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-2 pt-1 pb-3 flex items-start gap-2">
            <span class="text-base font-semibold text-gray-400 mt-0.5">230</span>
            <div>
                <h1 class="text-xl font-bold text-gray-800">Link de Pagamento Avulso</h1>
                <p class="text-xs text-gray-400">Financeiro › Movimentações — link dinâmico Pix/Cartão com baixa automática</p>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded text-sm mb-3">{{ session('success') }}</div>
        @endif
        @if(session('link_gerado'))
        <div class="bg-cyan-50 border border-cyan-300 rounded-lg p-4 mb-4 mx-2">
            <p class="text-sm font-semibold text-cyan-800 mb-2"><i class="fa-solid fa-link mr-1"></i> Link pronto para enviar por e-mail ou WhatsApp:</p>
            <div class="flex gap-2">
                <input type="text" readonly value="{{ session('link_gerado') }}" id="linkGerado" class="flex-1 border border-cyan-300 rounded-lg px-3 py-2 text-sm bg-white font-mono">
                <button onclick="navigator.clipboard.writeText(document.getElementById('linkGerado').value); this.innerText='Copiado!'" class="px-4 py-2 bg-cyan-500 text-white rounded-lg text-sm font-semibold hover:bg-cyan-400">Copiar</button>
            </div>
        </div>
        @endif
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-3">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('financeiro.link-pagamento.gerar') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 px-2 mb-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pessoa <span class="text-red-500">*</span></label>
                <select name="pessoa_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Selecione...</option>
                    @foreach($pessoas as $p)<option value="{{ $p->id }}">{{ $p->nome }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Valor (R$) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" min="0.01" name="valor" required class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Vencimento <span class="text-red-500">*</span></label>
                <input type="date" name="vencimento" value="{{ now()->addDays(3)->format('Y-m-d') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                <select name="categoria_receber_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">—</option>
                    @foreach($categorias as $c)<option value="{{ $c->id }}">{{ $c->nome }}</option>@endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-6 py-2 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow"><i class="fa-solid fa-link mr-1"></i> Gerar Link</button>
            </div>
        </form>

        <h2 class="text-sm font-semibold text-gray-700 px-2 mb-2">Links gerados</h2>
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Pessoa</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">Valor</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Vencimento</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Situação</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Link</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($links as $t)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2.5 font-medium text-gray-800">{{ $t->pessoa?->nome }}</td>
                    <td class="px-4 py-2.5 text-right">R$ {{ number_format($t->valor_original, 2, ',', '.') }}</td>
                    <td class="px-4 py-2.5 text-center">{{ $t->data_vencimento?->format('d/m/Y') }}</td>
                    <td class="px-4 py-2.5 text-center">
                        <span class="text-sm font-semibold {{ $t->situacao === 'pago' ? 'text-green-600' : 'text-blue-600' }}">{{ $t->situacao === 'pago' ? 'Pago' : 'Aguardando' }}</span>
                    </td>
                    <td class="px-4 py-2.5"><a href="{{ route('pagamento.publico', $t->token_pagamento) }}" target="_blank" class="text-cyan-600 hover:underline text-xs font-mono">{{ Str::limit(route('pagamento.publico', $t->token_pagamento), 50) }}</a></td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhum link gerado ainda.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4 px-2">{{ $links->links() }}</div>
    </div>
</div>
@endsection
