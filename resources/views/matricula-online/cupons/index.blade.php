@extends('layouts.app')
@section('title', 'Cupons de Desconto')

@section('content')
<x-data-table title="Cupons de Desconto" codigo="182" :createRoute="route('matricula-online.cupons.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Código</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Desconto</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Uso</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Validade</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Abertura</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($cupons as $c)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $c->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 text-gray-500">{{ $c->id }}</td>
                <td class="px-4 py-3"><span class="font-mono font-medium text-gray-800 bg-gray-100 px-2 py-0.5 rounded">{{ $c->codigo }}</span></td>
                <td class="px-4 py-3 text-gray-800">
                    @if($c->tipo === 'percentual')
                        {{ rtrim(rtrim(number_format($c->valor, 2, ',', '.'), '0'), ',') }}%
                    @else
                        R$ {{ number_format($c->valor, 2, ',', '.') }}
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $c->quantidade_usada }} / {{ $c->quantidade_total ?? '∞' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $c->validade?->format('d/m/Y') ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $c->abertura?->nome ?? 'Todas' }}</td>
                <td class="px-4 py-3">
                    @if($c->ativo)
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Ativo</span>
                    @else
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Inativo</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('matricula-online.cupons.edit', $c)" :delete="route('matricula-online.cupons.destroy', $c)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="px-4 py-8 text-center text-gray-400">Nenhum cupom cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $cupons->links() }}</div>
</x-data-table>
@endsection
