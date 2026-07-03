@extends('layouts.app')
@section('title', 'Cadastro de Turnos')

@section('content')
<x-data-table title="Cadastro de Turnos" codigo="201" :createRoute="route('academico.turnos.create')">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50">
                <tr>
                <th class="py-3 px-3 w-10"></th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($turnos as $turno)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $turno->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                    <td class="px-4 py-3 text-gray-600">{{ $turno->id }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $turno->nome }}</td>
                    <td class="px-4 py-3">
                        <x-kebab :edit="route('academico.turnos.edit', $turno)" :delete="route('academico.turnos.destroy', $turno)" />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">Nenhum turno encontrado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $turnos->links() }}
    </div>
</x-data-table>
@endsection
