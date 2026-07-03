@extends('layouts.app')
@section('title', 'Cadastro de Avaliações EAD')

@section('content')
<x-data-table title="Cadastro de Avaliações EAD" codigo="214" :createRoute="route('ead.avaliacoes.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Título</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Curso EAD</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nota Mínima</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tentativas</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($avaliacoes as $a)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $a->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $a->titulo }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $a->cursoEad?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ number_format($a->nota_minima, 2, ',', '.') }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $a->tentativas }}</td>
                <td class="px-4 py-3">
                    @if($a->ativo)<span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Ativo</span>
                    @else<span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Inativo</span>@endif
                </td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('ead.avaliacoes.edit', $a)" :delete="route('ead.avaliacoes.destroy', $a)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Nenhuma avaliação EAD.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $avaliacoes->links() }}</div>
</x-data-table>
@endsection
