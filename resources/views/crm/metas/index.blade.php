@extends('layouts.app')
@section('title', 'Metas CRM')

@section('content')
<x-data-table title="Metas CRM" codigo="191" :createRoute="route('crm.metas.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Meta</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Funil</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Consultor</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Objetivo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Periodo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($metas as $m)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $m->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $m->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $m->funil->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $m->consultor->nome ?? 'Todos' }}</td>
                <td class="px-4 py-3">
                    <span class="text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full capitalize">{{ $m->tipo }}</span>
                </td>
                <td class="px-4 py-3 font-medium text-gray-800">
                    @if($m->tipo === 'valor')
                        R$ {{ number_format($m->meta_valor, 2, ',', '.') }}
                    @else
                        {{ (int) $m->meta_valor }} oport.
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-600">
                    <span class="capitalize">{{ $m->periodo }}</span>
                    <div class="text-xs text-gray-400">{{ $m->data_inicio->format('d/m/Y') }} a {{ $m->data_fim->format('d/m/Y') }}</div>
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('crm.metas.edit', $m) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('crm.metas.destroy', $m) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Nenhuma meta cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $metas->links() }}</div>
</x-data-table>
@endsection
