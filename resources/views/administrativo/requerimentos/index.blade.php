@extends('layouts.app')
@section('title', 'Requerimentos')

@php
$badges = [
    'pendente' => 'bg-amber-100 text-amber-700',
    'aprovado' => 'bg-green-100 text-green-700',
    'reprovado' => 'bg-red-100 text-red-700',
    'cancelado' => 'bg-gray-100 text-gray-500',
    'entregue' => 'bg-blue-100 text-blue-700',
];
@endphp

@section('content')
<x-data-table title="Requerimentos" codigo="96" :createRoute="route('requerimentos.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Aluno</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Data</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($requerimentos as $r)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $r->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 text-gray-500">{{ $r->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $r->aluno?->pessoa?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $r->tipoRequerimento?->nome ?? '—' }}</td>
                <td class="px-4 py-3">
                    <span class="text-xs px-2 py-0.5 rounded-full capitalize {{ $badges[$r->situacao] ?? 'bg-gray-100 text-gray-500' }}">{{ $r->situacao }}</span>
                </td>
                <td class="px-4 py-3 text-gray-500">{{ $r->created_at?->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('requerimentos.edit', $r)" :delete="route('requerimentos.destroy', $r)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Nenhum requerimento cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $requerimentos->links() }}</div>
</x-data-table>
@endsection
