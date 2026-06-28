@extends('layouts.app')
@section('title', 'Categorias a Receber')

@section('content')
<x-data-table title="Categorias a Receber" codigo="65" :createRoute="route('financeiro.categorias-receber.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Plano de Contas</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($categorias as $c)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $c->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $c->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $c->planoConta?->nome ?? '—' }}</td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('financeiro.categorias-receber.edit', $c) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('financeiro.categorias-receber.destroy', $c) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhuma categoria cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $categorias->links() }}</div>
</x-data-table>
@endsection
