@extends('layouts.app')
@section('title', 'Oportunidades')

@section('content')
<x-data-table title="Oportunidades" codigo="109" :createRoute="route('crm.oportunidades.create')" createLabel="Nova Oportunidade">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b">
                <th class="py-3 px-3 w-10"></th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">ID</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Titulo</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Interessado</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Funil</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Etapa</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Consultor</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Valor</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600">Situacao</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600">Acoes</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($oportunidades as $op)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $op->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                    <td class="px-4 py-3 text-gray-500">{{ $op->id }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $op->titulo ?: '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $op->interessado->nome ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $op->funil->nome ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($op->etapaFunil)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium text-white" style="background-color: {{ $op->etapaFunil->cor ?? '#6366f1' }}">
                                {{ $op->etapaFunil->nome }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $op->consultor->nome ?? '-' }}</td>
                    <td class="px-4 py-3 text-right font-medium">
                        @if($op->valor)
                            <span class="text-green-600">R$ {{ number_format($op->valor, 2, ',', '.') }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        @php
                            $situacaoClasses = [
                                'aberta' => 'bg-blue-100 text-blue-700',
                                'ganha' => 'bg-green-100 text-green-700',
                                'perdida' => 'bg-red-100 text-red-700',
                                'pausada' => 'bg-yellow-100 text-yellow-700',
                            ];
                            $cls = $situacaoClasses[$op->situacao] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $cls }}">
                            {{ ucfirst($op->situacao) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <x-kebab :edit="route('crm.oportunidades.edit', $op)" :delete="route('crm.oportunidades.destroy', $op)" />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-4 py-8 text-center text-gray-400">
                        <i class="fa-solid fa-handshake text-3xl mb-2"></i>
                        <p>Nenhuma oportunidade encontrada.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($oportunidades->hasPages())
    <div class="px-4 py-3 border-t">
        {{ $oportunidades->links() }}
    </div>
    @endif
</x-data-table>
@endsection
