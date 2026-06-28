@extends('layouts.app')
@section('title', 'Matriz Curricular')

@section('content')
<x-data-table title="Matriz Curricular" codigo="30" :createRoute="route('academico.matrizes.create')">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Curso</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">CH Total</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($matrizes as $matriz)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-600">{{ $matriz->id }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $matriz->nome }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $matriz->curso->nome ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $matriz->carga_horaria_total ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($matriz->situacao === 'ativa')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Ativa</span>
                        @elseif($matriz->situacao === 'rascunho')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Rascunho</span>
                        @elseif($matriz->situacao === 'finalizada')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Finalizada</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $matriz->situacao ?? '-' }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('academico.matrizes.edit', $matriz) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded" title="Editar">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </a>
                            <form action="{{ route('academico.matrizes.destroy', $matriz) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta matriz curricular?')">
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
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Nenhuma matriz curricular encontrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $matrizes->links() }}
    </div>
</x-data-table>
@endsection
