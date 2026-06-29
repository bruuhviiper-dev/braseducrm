@extends('layouts.app')
@section('title', 'Movimentações de Caixa')

@section('content')
<div class="space-y-6">
    {{-- Abrir caixa --}}
    <div class="bg-white rounded-xl border">
        <div class="px-5 py-3 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">68</span>
            <h1 class="text-lg font-semibold text-gray-800">Movimentações de Caixa</h1>
        </div>
        <form method="POST" action="{{ route('financeiro.caixas.abrir') }}" class="p-4 flex flex-wrap items-end gap-3">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Conta Bancária</label>
                <select name="conta_bancaria_id" class="border rounded-lg px-3 py-2 text-sm">
                    <option value="">Caixa interno</option>
                    @foreach($contas as $c)<option value="{{ $c->id }}">{{ $c->nome }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Valor de Abertura</label>
                <input type="number" step="0.01" min="0" name="valor_abertura" value="0" class="border rounded-lg px-3 py-2 text-sm w-36" required>
            </div>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700"><i class="fa-solid fa-cash-register mr-1"></i> Abrir Caixa</button>
        </form>
    </div>

    {{-- Lista de caixas --}}
    <div class="bg-white rounded-xl border">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Abertura</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Conta</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Movim.</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($caixas as $c)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-500">{{ $c->id }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $c->data_abertura?->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $c->contaBancaria?->nome ?? 'Caixa interno' }}</td>
                    <td class="px-4 py-3"><span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $c->movimentacoes_count }}</span></td>
                    <td class="px-4 py-3">
                        @if($c->situacao === 'aberto')
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Aberto</span>
                        @else
                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Fechado</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('financeiro.caixas.show', $c) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded" title="Abrir"><i class="fa-solid fa-eye"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhum caixa registrado.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $caixas->links() }}</div>
    </div>
</div>
@endsection
