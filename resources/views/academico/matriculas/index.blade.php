@extends('layouts.app')
@section('title', 'Matriculas')

@section('content')
<x-data-table title="Matriculas" codigo="23" :createRoute="route('academico.matriculas.create')">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Numero</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Aluno</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Turma</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Data</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($matriculas as $matricula)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-600">{{ $matricula->id }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $matricula->numero_matricula ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $matricula->aluno->pessoa->nome ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $matricula->turma->nome ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $matricula->data_matricula ? $matricula->data_matricula->format('d/m/Y') : '-' }}</td>
                    <td class="px-4 py-3">
                        @if($matricula->situacao === 'ativa')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Ativa</span>
                        @elseif($matricula->situacao === 'em_andamento')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Em Andamento</span>
                        @elseif($matricula->situacao === 'cancelada')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Cancelada</span>
                        @elseif($matricula->situacao === 'trancada')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Trancada</span>
                        @elseif($matricula->situacao === 'concluida')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Concluida</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($matricula->situacao ?? '-') }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('academico.matriculas.edit', $matricula) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded" title="Editar">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </a>
                            <form action="{{ route('academico.matriculas.destroy', $matricula) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta matricula?')">
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
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">Nenhuma matricula encontrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $matriculas->links() }}
    </div>
</x-data-table>
@endsection
