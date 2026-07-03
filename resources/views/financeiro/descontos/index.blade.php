@extends('layouts.app')
@section('title', 'Descontos')

@section('content')
<x-data-table title="Descontos Incondicionais" codigo="57" :createRoute="route('financeiro.descontos.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Valor</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($descontos as $d)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $d->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 text-gray-500">{{ $d->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $d->nome }}</td>
                <td class="px-4 py-3 text-gray-600 capitalize">{{ $d->tipo }}</td>
                <td class="px-4 py-3 text-gray-800">
                    @if($d->tipo === 'percentual')
                        {{ rtrim(rtrim(number_format($d->valor, 2, ',', '.'), '0'), ',') }}%
                    @else
                        R$ {{ number_format($d->valor, 2, ',', '.') }}
                    @endif
                </td>
                <td class="px-4 py-3">
                    @if($d->ativo)
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Ativo</span>
                    @else
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Inativo</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('financeiro.descontos.edit', $d)" :delete="route('financeiro.descontos.destroy', $d)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Nenhum desconto cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $descontos->links() }}</div>
</x-data-table>
@endsection
