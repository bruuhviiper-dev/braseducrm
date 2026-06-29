@extends('layouts.app')
@section('title', 'Cadastro de Calendário')

@section('content')
<x-data-table title="Cadastro de Calendário" codigo="35" :createRoute="route('academico.calendarios.create')">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ano</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Dias letivos</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($calendarios as $calendario)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $calendario->ano }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $calendario->dias_letivos_count }} dia(s)</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('academico.calendarios.edit', $calendario) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded" title="Editar">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </a>
                            <form action="{{ route('academico.calendarios.destroy', $calendario) }}" method="POST" onsubmit="return confirm('Excluir o calendário {{ $calendario->ano }}?')">
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
                    <td colspan="3" class="px-4 py-8 text-center text-gray-500">Nenhum calendário cadastrado.</td>
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
