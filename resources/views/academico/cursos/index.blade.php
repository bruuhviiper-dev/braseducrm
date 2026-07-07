@extends('layouts.app')
@section('title', 'Cadastro de Curso')

@section('content')
<x-data-table title="Cadastro de Curso" codigo="25" breadcrumb="Acadêmico › Matriz Curricular" :createRoute="route('academico.cursos.create')">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-3 px-4 w-12"></th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase w-52">Sigla</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase w-20">Ações</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Descrição</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase w-40">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($cursos as $curso)
                <tr class="hover:bg-gray-50">
                    <td class="py-3.5 px-4"><input type="radio" name="sel" value="{{ $curso->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                    <td class="px-4 py-3.5 font-semibold text-gray-800">{{ $curso->sigla }}</td>
                    <td class="px-4 py-2">
                        <x-kebab dir="left" :edit="route('academico.cursos.edit', $curso)" :delete="route('academico.cursos.destroy', $curso)" />
                    </td>
                    <td class="px-4 py-3.5 text-gray-600">{{ $curso->nome }}</td>
                    <td class="px-4 py-3.5">
                        @if($curso->ativo)
                            <span class="text-sm font-semibold text-green-600">Ativo</span>
                        @else
                            <span class="text-sm font-semibold text-red-500">Inativo</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">Nenhum curso encontrado.</td>
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
