@extends('layouts.app')
@section('title', 'Cadastro de Desconto Condicional')

@section('content')
<x-data-table title="Cadastro de Desconto Condicional" codigo="58" breadcrumb="Financeiro › Cadastros Essenciais" :createRoute="route('financeiro.descontos-condicionais.create')">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 uppercase border-b">
                    <th class="pb-2 pr-2 w-8"></th>
                    <th class="pb-2 pr-4">Descrição</th>
                    <th class="pb-2 pr-4">Tipo</th>
                    <th class="pb-2 pr-4">Dias e Valores</th>
                    <th class="pb-2 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($descontos as $d)
                <tr class="hover:bg-gray-50">
                    <td class="py-2.5 pr-2"><input type="radio" name="sel" class="rounded-full border-gray-300"></td>
                    <td class="py-2.5 pr-4 font-medium text-gray-800">{{ $d->nome }}</td>
                    <td class="py-2.5 pr-4 text-gray-600">{{ $d->tipo === 'percentual' ? 'Percentual (%)' : 'Valor (R$)' }}</td>
                    <td class="py-2.5 pr-4 text-gray-600">{{ $d->itens_count }} {{ $d->itens_count === 1 ? 'item' : 'itens' }}</td>
                    <td class="py-2.5 text-right">
                        <x-kebab :edit="route('financeiro.descontos-condicionais.edit', $d)" :delete="route('financeiro.descontos-condicionais.destroy', $d)" />
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-gray-400">Nada encontrado<br><span class="text-xs">Nenhum item encontrado.</span></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $descontos->links() }}</div>
</x-data-table>
@endsection
