@extends('layouts.app')
@section('title', 'Matriculas')

@section('content')
<x-data-table title="Matriculas" codigo="23" :createRoute="route('academico.matriculas.create')">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50">
                <tr>
                <th class="py-3 px-3 w-10"></th>
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
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $matricula->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
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
                        <x-kebab :edit="route('academico.matriculas.edit', $matricula)" :delete="route('academico.matriculas.destroy', $matricula)">
                            <a href="{{ route('academico.matriculas.historico', $matricula) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fa-solid fa-clock-rotate-left mr-2 text-gray-400"></i>Histórico Escolar</a>
                        </x-kebab>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">Nenhuma matricula encontrada.</td>
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
