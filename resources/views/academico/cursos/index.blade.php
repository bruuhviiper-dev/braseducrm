@extends('layouts.app')
@section('title', 'Cadastro de Curso')

@section('content')
<x-data-table title="Cadastro de Curso" codigo="25" :createRoute="route('academico.cursos.create')">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Sigla</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Area</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Grau</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">CH Total</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($cursos as $curso)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-600">{{ $curso->id }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $curso->nome }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $curso->sigla }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $curso->areaConhecimento->nome ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $curso->grau->nome ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $curso->carga_horaria_total ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($curso->ativo)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Ativo</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inativo</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('academico.cursos.edit', $curso) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded" title="Editar">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </a>
                            <form action="{{ route('academico.cursos.destroy', $curso) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este curso?')">
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
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">Nenhum curso encontrado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $cursos->links() }}
    </div>
</x-data-table>
@endsection
