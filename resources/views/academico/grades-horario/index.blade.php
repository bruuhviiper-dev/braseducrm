@extends('layouts.app')
@section('title', 'Cadastro de Grade de Horário')

@section('content')
<x-data-table title="Cadastro de Grade de Horário" codigo="36" :createRoute="route('academico.grades-horario.create')">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50">
                <tr>
                <th class="py-3 px-3 w-10"></th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Descrição</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Turno</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Horários</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Está Ativo?</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($grades as $grade)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $grade->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $grade->nome }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $grade->turno?->nome ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $grade->aulas_count }} horário(s)</td>
                    <td class="px-4 py-3">
                        @if($grade->ativo)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Ativo</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inativo</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <x-kebab :edit="route('academico.grades-horario.edit', $grade)" :delete="route('academico.grades-horario.destroy', $grade)" />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Nenhuma grade de horário cadastrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $grades->links() }}
    </div>
</x-data-table>
@endsection
