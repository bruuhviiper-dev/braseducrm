@extends('layouts.app')
@section('title', 'Cadastro de Turma')

@section('content')
<x-data-table title="Cadastro de Turma" codigo="40" :createRoute="route('academico.turmas.create')">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Codigo</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Curso</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Turno</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Periodo</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Vagas</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($turmas as $turma)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-600">{{ $turma->id }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $turma->nome }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $turma->codigo }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $turma->curso->nome ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $turma->turno->nome ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $turma->periodoLetivo->nome ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $turma->vagas ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($turma->situacao === 'ativa' || $turma->situacao === 'aberta')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ ucfirst($turma->situacao) }}</span>
                        @elseif($turma->situacao === 'em_andamento')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Em Andamento</span>
                        @elseif($turma->situacao === 'cancelada')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Cancelada</span>
                        @elseif($turma->situacao === 'encerrada')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Encerrada</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">{{ ucfirst($turma->situacao ?? '-') }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('academico.turmas.edit', $turma) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded" title="Editar">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </a>
                            <form action="{{ route('academico.turmas.destroy', $turma) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta turma?')">
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
                    <td colspan="9" class="px-4 py-8 text-center text-gray-500">Nenhuma turma encontrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $turmas->links() }}
    </div>
</x-data-table>
@endsection
