@extends('layouts.app')
@section('title', 'Cadastro de Disciplina')

@section('content')
<x-data-table title="Cadastro de Disciplina" codigo="26" :createRoute="route('academico.disciplinas.create')">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Sigla</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Carga Horaria</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($disciplinas as $disciplina)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-600">{{ $disciplina->id }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $disciplina->nome }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $disciplina->sigla }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $disciplina->carga_horaria ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($disciplina->ativo)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Ativo</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inativo</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('academico.disciplinas.edit', $disciplina) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded" title="Editar">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </a>
                            <form action="{{ route('academico.disciplinas.destroy', $disciplina) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta disciplina?')">
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
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Nenhuma disciplina encontrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $disciplinas->links() }}
    </div>
</x-data-table>
@endsection
