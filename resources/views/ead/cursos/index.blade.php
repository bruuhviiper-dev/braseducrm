@extends('layouts.app')
@section('title', 'Cadastro de Curso EAD')

@section('content')
<x-data-table title="Cadastro de Curso (EAD)" codigo="152" :createRoute="route('ead.cursos.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tutor</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Módulos</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Carga Horaria</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Valor</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ativo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($cursos as $c)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $c->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 text-gray-500">{{ $c->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $c->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $c->tutor?->pessoa?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $c->modulos_count }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $c->carga_horaria ? $c->carga_horaria . 'h' : '-' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $c->valor ? 'R$ ' . number_format($c->valor, 2, ',', '.') : '-' }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded text-xs {{ $c->ativo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $c->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('ead.cursos.edit', $c)" :delete="route('ead.cursos.destroy', $c)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="px-4 py-8 text-center text-gray-400">Nenhum curso EAD cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $cursos->links() }}</div>
</x-data-table>
@endsection
