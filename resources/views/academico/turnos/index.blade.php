@extends('layouts.app')
@section('title', 'Cadastro de Turnos')

@section('content')
<x-data-table title="Cadastro de Turnos" codigo="201" :createRoute="route('academico.turnos.create')">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($turnos as $turno)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-600">{{ $turno->id }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $turno->nome }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('academico.turnos.edit', $turno) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded" title="Editar">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </a>
                            <form action="{{ route('academico.turnos.destroy', $turno) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este turno?')">
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
                    <td colspan="3" class="px-4 py-8 text-center text-gray-500">Nenhum turno encontrado.</td>
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
