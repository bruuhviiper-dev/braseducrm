@extends('layouts.app')
@section('title', 'Recebimento Coletivo (Bancário)')

@section('content')
<div class="w-full" x-data="{ marcados: 0, toggleTodos(v) { document.querySelectorAll('input[name=\'titulos[]\']').forEach(c => c.checked = v); this.conta(); }, conta() { this.marcados = document.querySelectorAll('input[name=\'titulos[]\']:checked').length; } }">
    <div class="bg-white">
        <div class="px-2 pt-1 pb-3 flex items-start gap-2">
            <span class="text-base font-semibold text-gray-400 mt-0.5">259</span>
            <div>
                <h1 class="text-xl font-bold text-gray-800">Recebimento Coletivo (Bancário)</h1>
                <p class="text-xs text-gray-400">Financeiro › Movimentações — baixa de títulos em lote</p>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded text-sm mb-4">{{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-4">{{ $errors->first() }}</div>
        @endif

        <form method="GET" class="flex flex-wrap items-end gap-3 mb-4 px-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pessoa</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nome..." class="border rounded-lg px-3 py-2 text-sm w-56">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Vencimento de</label>
                <input type="date" name="de" value="{{ request('de') }}" class="border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">até</label>
                <input type="date" name="ate" value="{{ request('ate') }}" class="border rounded-lg px-3 py-2 text-sm">
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-100 border rounded-lg text-sm text-gray-600 hover:bg-gray-200"><i class="fa-solid fa-filter mr-1"></i> Filtrar</button>
        </form>

        <form method="POST" action="{{ route('financeiro.recebimento-coletivo.processar') }}">
            @csrf
            <div class="flex flex-wrap items-end gap-3 mb-3 px-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data do recebimento <span class="text-red-500">*</span></label>
                    <input type="date" name="data_pagamento" value="{{ now()->format('Y-m-d') }}" required class="border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Conta de destino</label>
                    <select name="conta_bancaria_id" class="border rounded-lg px-3 py-2 text-sm w-56">
                        <option value="">Manter a conta do título</option>
                        @foreach($contas as $c)<option value="{{ $c->id }}">{{ $c->nome }}</option>@endforeach
                    </select>
                </div>
                <span class="text-sm text-gray-500 pb-2" x-text="marcados + ' título(s) selecionado(s)'"></span>
            </div>

            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 w-12"><input type="checkbox" @change="toggleTodos($event.target.checked)" class="rounded text-primary-600"></th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Pessoa</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Categoria</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Vencimento</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">Valor (R$)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($titulos as $t)
                    <tr class="hover:bg-gray-50 {{ $t->data_vencimento && $t->data_vencimento->isPast() ? 'text-red-600' : '' }}">
                        <td class="px-4 py-2.5"><input type="checkbox" name="titulos[]" value="{{ $t->id }}" @change="conta()" class="rounded text-primary-600"></td>
                        <td class="px-4 py-2.5 font-medium text-gray-800">{{ $t->pessoa?->nome ?? '—' }}</td>
                        <td class="px-4 py-2.5 text-gray-600">{{ $t->categoriaReceber?->nome ?? '—' }}</td>
                        <td class="px-4 py-2.5 text-center">{{ $t->data_vencimento?->format('d/m/Y') }}</td>
                        <td class="px-4 py-2.5 text-right">{{ number_format($t->valor_original - ($t->valor_desconto ?? 0), 2, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhum título em aberto no filtro.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="flex justify-end pt-3 sticky bottom-4 z-10">
                <button type="submit" onclick="return confirm('Confirmar o recebimento em lote dos títulos selecionados?')" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30"><i class="fa-solid fa-check mr-1"></i>Receber Selecionados</button>
            </div>
        </form>
    </div>
</div>
@endsection
