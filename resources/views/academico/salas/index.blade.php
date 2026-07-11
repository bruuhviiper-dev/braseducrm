@extends('layouts.app')
@section('title', 'Cadastro de Sala')

@section('content')
{{-- 39 Cadastro de Sala (padrão EDUQ: SIGLA | DESCRIÇÃO | STATUS, sem coluna ID, pill azul) --}}
<x-data-table title="Cadastro de Sala" codigo="39" breadcrumb="Acadêmico › Cadastros Essenciais" :createRoute="route('academico.salas.create')">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="border-b">
                    <th class="py-3 px-3 w-8"></th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Sigla</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase w-10"></th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Descrição</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($salas as $sala)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $sala->id }}" class="w-4 h-4 text-cyan-500 border-gray-300"></td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $sala->sigla ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <x-kebab :edit="route('academico.salas.edit', $sala)" :delete="route('academico.salas.destroy', $sala)" dir="left" />
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $sala->nome }}</td>
                    <td class="px-4 py-3"><x-eduq-status :ativo="$sala->ativo" /></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhuma sala encontrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $salas->links() }}
    </div>
</x-data-table>
@endsection
