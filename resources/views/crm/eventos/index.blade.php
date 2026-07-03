@extends('layouts.app')
@section('title', 'Eventos CRM')

@section('content')
<x-data-table title="Eventos CRM" codigo="104" :createRoute="route('crm.eventos.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Evento</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($eventos as $e)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $e->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 text-gray-500">{{ $e->id }}</td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center gap-2 font-medium text-gray-800">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-white text-xs" style="background-color: {{ $e->cor }}">
                            <i class="fa-solid {{ $e->icone ?: 'fa-calendar-check' }}"></i>
                        </span>
                        {{ $e->nome }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    @if($e->ativo)
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Ativo</span>
                    @else
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Inativo</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('crm.eventos.edit', $e)" :delete="route('crm.eventos.destroy', $e)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhum evento cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $eventos->links() }}</div>
</x-data-table>
@endsection
