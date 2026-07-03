@extends('layouts.app')
@section('title', 'Cadastro de Exemplares')

@php
$badge = fn($s) => match($s) { 'disponivel' => 'bg-green-100 text-green-700', 'emprestado' => 'bg-amber-100 text-amber-700', 'reservado' => 'bg-blue-100 text-blue-700', default => 'bg-gray-100 text-gray-500' };
@endphp

@section('content')
<x-data-table title="Cadastro de Exemplares" codigo="286" :createRoute="route('biblioteca.exemplares.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Código</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Obra</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Biblioteca</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situação</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($exemplares as $ex)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $ex->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $ex->codigo ?? '#'.$ex->id }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $ex->obra?->titulo ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $ex->biblioteca?->nome ?? '—' }}</td>
                <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full capitalize {{ $badge($ex->situacao) }}">{{ $ex->situacao }}</span></td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('biblioteca.exemplares.edit', $ex)" :delete="route('biblioteca.exemplares.destroy', $ex)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhum exemplar cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $exemplares->links() }}</div>
</x-data-table>
@endsection
