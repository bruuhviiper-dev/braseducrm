@extends('layouts.app')
@section('title', 'Cadastro de Curso EAD')

@section('content')
<x-data-table title="Cadastro de Curso (EAD)" codigo="152" :createRoute="route('ead.cursos.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Carga Horaria</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Valor</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ativo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($cursos as $c)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $c->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $c->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $c->carga_horaria ? $c->carga_horaria . 'h' : '-' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $c->valor ? 'R$ ' . number_format($c->valor, 2, ',', '.') : '-' }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded text-xs {{ $c->ativo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $c->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('ead.cursos.edit', $c) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('ead.cursos.destroy', $c) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhum curso EAD cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $cursos->links() }}</div>
</x-data-table>
@endsection
