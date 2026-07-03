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
                <th class="py-3 px-3 w-10"></th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Título</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Curso EAD</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Mensagens</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($foruns as $f)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $f->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                    <td class="px-4 py-3 font-medium text-gray-800"><a href="{{ route('ead.foruns.show', $f) }}" class="text-primary-600 hover:underline">{{ $f->titulo }}</a></td>
                    <td class="px-4 py-3 text-gray-600">{{ $f->cursoEad?->nome ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $f->mensagens_count }}</td>
                    <td class="px-4 py-3">
                        <x-kebab :show="route('ead.foruns.show', $f)" :edit="route('ead.foruns.edit', $f)" :delete="route('ead.foruns.destroy', $f)" />
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhum fórum cadastrado.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $foruns->links() }}</div>
    </x-data-table>
</div>
@endsection
