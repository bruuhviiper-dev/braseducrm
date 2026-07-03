@extends('layouts.app')
@section('title', 'Sub Agrupador de Cursos')

@section('content')
<x-data-table title="Sub Agrupador de Cursos" codigo="266" :createRoute="route('ead.sub-agrupadores.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Agrupador</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($registros as $r)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $r->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $r->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $r->agrupador?->nome ?? '—' }}</td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('ead.sub-agrupadores.edit', $r)" :delete="route('ead.sub-agrupadores.destroy', $r)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhum sub agrupador cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $registros->links() }}</div>
</x-data-table>
@endsection
