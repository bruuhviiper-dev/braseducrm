@extends('layouts.app')
@section('title', 'Cartão de Crédito Empresarial')

@section('content')
<x-data-table title="Cartão de Crédito Empresarial" codigo="136" :createRoute="route('financeiro.cartoes-empresariais.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Cartão</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Bandeira</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Final</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Banco</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Limite</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Fech./Venc.</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($cartoes as $c)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $c->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $c->bandeira ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $c->ultimos_digitos ? '•••• '.$c->ultimos_digitos : '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $c->banco?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-800">R$ {{ number_format($c->limite, 2, ',', '.') }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $c->dia_fechamento ?? '—' }} / {{ $c->dia_vencimento ?? '—' }}</td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('financeiro.cartoes-empresariais.edit', $c) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('financeiro.cartoes-empresariais.destroy', $c) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Nenhum cartão empresarial cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $cartoes->links() }}</div>
</x-data-table>
@endsection
