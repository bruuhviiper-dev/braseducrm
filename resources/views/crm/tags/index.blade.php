@extends('layouts.app')
@section('title', 'Tags CRM')

@section('content')
<x-data-table title="Tags CRM" codigo="171" :createRoute="route('crm.tags.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tag</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Cor</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($tags as $t)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $t->id }}</td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center text-xs font-medium px-2.5 py-1 rounded-full text-white" style="background-color: {{ $t->cor }}">{{ $t->nome }}</span>
                </td>
                <td class="px-4 py-3 text-gray-500">{{ $t->cor }}</td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('crm.tags.edit', $t) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('crm.tags.destroy', $t) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhuma tag cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $tags->links() }}</div>
</x-data-table>
@endsection
