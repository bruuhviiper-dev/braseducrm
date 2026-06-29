@extends('layouts.app')
@section('title', 'Cadastro de Grade de Horário')

@section('content')
<x-data-table title="Cadastro de Grade de Horário" codigo="36" :createRoute="route('academico.grades-horario.create')">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50">
                <tr>
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
                        <div class="flex items-center gap-1">
                            <a href="{{ route('academico.grades-horario.edit', $grade) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded" title="Editar">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </a>
                            <form action="{{ route('academico.grades-horario.destroy', $grade) }}" method="POST" onsubmit="return confirm('Excluir esta grade de horário?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded" title="Excluir">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">Nenhuma grade de horário cadastrada.</td>
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
