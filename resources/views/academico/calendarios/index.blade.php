@extends('layouts.app')
@section('title', 'Cadastro de Calendário')

@section('content')
<x-data-table title="Cadastro de Calendário" codigo="35" :createRoute="route('academico.calendarios.create')">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50">
                <tr>
                <th class="py-3 px-3 w-10"></th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ano</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Dias letivos</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($calendarios as $calendario)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $calendario->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $calendario->ano }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $calendario->dias_letivos_count }} dia(s)</td>
                    <td class="px-4 py-3">
                        <x-kebab :edit="route('academico.calendarios.edit', $calendario)" :delete="route('academico.calendarios.destroy', $calendario)" />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">Nenhum calendário cadastrado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $calendarios->links() }}
    </div>
</x-data-table>
@endsection
