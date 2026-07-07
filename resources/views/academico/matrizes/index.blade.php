@extends('layouts.app')
@section('title', 'Cadastro de Matriz Curricular')

@section('content')
<x-data-table title="Cadastro de Matriz Curricular" codigo="30" breadcrumb="Acadêmico › Matriz Curricular" :createRoute="route('academico.matrizes.create')">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-3 px-4 w-12"></th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase w-52">Sigla</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase w-20">Ações</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Descrição</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase w-40">Situação</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($matrizes as $matriz)
                <tr class="hover:bg-gray-50">
                    <td class="py-3.5 px-4"><input type="radio" name="sel" value="{{ $matriz->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                    <td class="px-4 py-3.5 font-semibold text-gray-800">{{ $matriz->sigla ?: $matriz->nome }}</td>
                    <td class="px-4 py-2">
                        <x-kebab dir="left" :edit="route('academico.matrizes.edit', $matriz)" :delete="route('academico.matrizes.destroy', $matriz)" />
                    </td>
                    <td class="px-4 py-3.5 text-gray-600">{{ $matriz->nome }}</td>
                    <td class="px-4 py-3.5 text-gray-600">{{ $matriz->situacao === 'ativa' ? 'Ativa' : ucfirst($matriz->situacao ?? '-') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">Nenhuma matriz curricular encontrada.</td>
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
