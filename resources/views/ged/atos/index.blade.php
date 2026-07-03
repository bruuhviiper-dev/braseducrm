@extends('layouts.app')
@section('title', 'Atos Regulatórios')

@php $tipos = \App\Models\AtoRegulatorio::tipos(); @endphp

@section('content')
<x-data-table title="Atos Regulatórios" codigo="216" :createRoute="route('ged.atos.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Número</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Curso</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Publicação</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Validade</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($atos as $a)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $a->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 text-gray-500">{{ $a->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $tipos[$a->tipo] ?? $a->tipo }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $a->numero ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $a->curso?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $a->data_publicacao?->format('d/m/Y') ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $a->validade?->format('d/m/Y') ?? '—' }}</td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('ged.atos.edit', $a)" :delete="route('ged.atos.destroy', $a)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Nenhum ato regulatório cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $atos->links() }}</div>
</x-data-table>
@endsection
