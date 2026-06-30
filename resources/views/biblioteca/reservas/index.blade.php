@extends('layouts.app')
@section('title', 'Reserva de Exemplares')

@php $badge = fn($s) => match($s) { 'atendida' => 'bg-green-100 text-green-700', 'cancelada' => 'bg-red-100 text-red-700', default => 'bg-blue-100 text-blue-700' }; @endphp

@section('content')
<x-data-table title="Reserva de Exemplares" codigo="289" :createRoute="route('biblioteca.reservas.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Obra</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Biblioteca</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Pessoa</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Data</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situação</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($reservas as $r)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $r->obra?->titulo ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $r->biblioteca?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $r->pessoa?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ optional($r->data_reserva)->format('d/m/Y') }}</td>
                <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full {{ $badge($r->situacao) }}">{{ ucfirst($r->situacao) }}</span></td>
                <td class="px-4 py-3">
                    <div class="flex gap-1 items-center">
                        @if($r->situacao === 'ativa')
                        <form method="POST" action="{{ route('biblioteca.reservas.situacao', $r) }}">
                            @csrf @method('PUT')
                            <input type="hidden" name="situacao" value="atendida">
                            <button class="px-2 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">Atender</button>
                        </form>
                        <form method="POST" action="{{ route('biblioteca.reservas.situacao', $r) }}">
                            @csrf @method('PUT')
                            <input type="hidden" name="situacao" value="cancelada">
                            <button class="px-2 py-1 bg-gray-200 text-gray-700 rounded text-xs hover:bg-gray-300">Cancelar</button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('biblioteca.reservas.destroy', $r) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhuma reserva.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $reservas->links() }}</div>
</x-data-table>
@endsection
