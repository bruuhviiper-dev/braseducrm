@extends('layouts.app')
@section('title', 'Nova Renegociação')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Passo 1: selecionar pessoa --}}
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">Nova Renegociação</h2>
            <a href="{{ route('financeiro.renegociacoes.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="GET" action="{{ route('financeiro.renegociacoes.create') }}" class="p-4 flex items-end gap-3">
            <div class="flex-1">
                <label class="block text-xs font-medium text-gray-600 mb-1">Pessoa (devedor)</label>
                <select name="pessoa_id" class="w-full border rounded-lg px-3 py-2 text-sm" onchange="this.form.submit()">
                    <option value="">Selecione...</option>
                    @foreach($pessoas as $p)
                    <option value="{{ $p->id }}" {{ $pessoaSelecionada == $p->id ? 'selected' : '' }}>{{ $p->nome }}</option>
                    @endforeach
                </select>
            </div>
            <button class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700">Buscar Títulos</button>
        </form>
    </div>

    {{-- Passo 2: selecionar títulos e renegociar --}}
    @if($pessoaSelecionada)
        @if($titulos->isEmpty())
        <div class="bg-white rounded-xl border p-8 text-center text-gray-400">Nenhum título em aberto para esta pessoa.</div>
        @else
        <form method="POST" action="{{ route('financeiro.renegociacoes.store') }}" class="bg-white rounded-lg shadow-sm border"
              x-data="{ total: 0, parcelas: 1, recalc() { this.total = 0; document.querySelectorAll('.tit:checked').forEach(c => this.total += parseFloat(c.dataset.valor)); } }"
              x-init="recalc()">
            @csrf
            <input type="hidden" name="pessoa_id" value="{{ $pessoaSelecionada }}">
            <div class="px-6 py-4 border-b"><h3 class="text-sm font-semibold text-gray-700">Títulos em aberto</h3></div>

            @if($errors->any())
            <div class="m-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-2"></th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Documento</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Vencimento</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Valor</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($titulos as $t)
                    <tr>
                        <td class="px-4 py-2"><input type="checkbox" name="titulos[]" value="{{ $t->id }}" class="tit rounded border-gray-300 text-blue-600" data-valor="{{ $t->valor_original }}" @change="recalc()" checked></td>
                        <td class="px-4 py-2 text-gray-700">{{ $t->numero_documento ?? $t->id }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $t->data_vencimento?->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 text-gray-800">R$ {{ number_format($t->valor_original, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="p-6 border-t grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valor Total Renegociado <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="valor_total_renegociado" :value="total.toFixed(2)" class="w-full border rounded-lg px-3 py-2 text-sm" required>
                    <p class="text-xs text-gray-400 mt-1">Soma selecionada: R$ <span x-text="total.toFixed(2)"></span></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nº de Parcelas <span class="text-red-500">*</span></label>
                    <input type="number" min="1" max="60" name="numero_parcelas" value="1" class="w-full border rounded-lg px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">1º Vencimento <span class="text-red-500">*</span></label>
                    <input type="date" name="primeiro_vencimento" value="{{ now()->addMonth()->format('Y-m-d') }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
                </div>
            </div>

            <div class="px-6 pb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                <textarea name="observacoes" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
            </div>

            <div class="px-6 pb-6 flex gap-3">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700" onclick="return confirm('Confirmar renegociação? Os títulos selecionados serão baixados e novas parcelas geradas.')">Renegociar</button>
                <a href="{{ route('financeiro.renegociacoes.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
        @endif
    @endif
</div>
@endsection
