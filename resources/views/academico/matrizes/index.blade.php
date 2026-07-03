@extends('layouts.app')
@section('title', 'Matriz Curricular')

@section('content')
<x-data-table title="Matriz Curricular" codigo="30" :createRoute="route('academico.matrizes.create')">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50">
                <tr>
                <th class="py-3 px-3 w-10"></th>
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
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $matriz->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
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
                        <x-kebab :edit="route('academico.matrizes.edit', $matriz)" :delete="route('academico.matrizes.destroy', $matriz)" />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">Nenhuma matriz curricular encontrada.</td>
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
