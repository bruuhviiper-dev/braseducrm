@extends('layouts.app')
@section('title', 'Fóruns EAD')

@section('content')
<div class="space-y-4">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white rounded-xl border p-4">
            <p class="text-xs text-gray-500 uppercase">Tópicos</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['topicos'] }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-xs text-gray-500 uppercase">Mensagens</p>
            <p class="text-2xl font-bold text-gray-800">{{ $stats['mensagens'] }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-xs text-gray-500 uppercase">Sem resposta do tutor</p>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['sem_tutor'] }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-xs text-gray-500 uppercase">Movimentados (30 dias)</p>
            <p class="text-2xl font-bold text-primary-600">{{ $stats['movimentados'] }}</p>
        </div>
    </div>

    <x-data-table title="Fóruns EAD" codigo="306" :createRoute="route('ead.foruns.create')">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Título</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Curso EAD</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Mensagens</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($foruns as $f)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-800"><a href="{{ route('ead.foruns.show', $f) }}" class="text-primary-600 hover:underline">{{ $f->titulo }}</a></td>
                    <td class="px-4 py-3 text-gray-600">{{ $f->cursoEad?->nome ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $f->mensagens_count }}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-1">
                            <a href="{{ route('ead.foruns.show', $f) }}" class="p-1.5 text-gray-600 hover:bg-gray-100 rounded"><i class="fa-solid fa-comments"></i></a>
                            <a href="{{ route('ead.foruns.edit', $f) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                            <form method="POST" action="{{ route('ead.foruns.destroy', $f) }}" onsubmit="return confirm('Remover?')">
                                @csrf @method('DELETE')
                                <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhum fórum cadastrado.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $foruns->links() }}</div>
    </x-data-table>
</div>
@endsection
