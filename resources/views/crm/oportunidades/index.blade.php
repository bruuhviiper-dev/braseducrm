@extends('layouts.app')
@section('title', 'Oportunidades')

@section('content')
<div x-data="{ perda: null }">
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
                        <x-kebab :edit="route('crm.oportunidades.edit', $op)" :delete="route('crm.oportunidades.destroy', $op)">
                            @if($op->situacao === 'aberta' || $op->situacao === 'pausada')
                            <form method="POST" action="{{ route('crm.oportunidades.ganhar', $op) }}" onsubmit="return confirm('Marcar como Ganha? No EDUQ, Ganho significa efetivação de matrícula.')">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-green-700 hover:bg-green-50"><i class="fa-solid fa-trophy mr-2"></i>Marcar como Ganha</button>
                            </form>
                            <button type="button" @click="perda = '{{ route('crm.oportunidades.perder', $op) }}'" class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50"><i class="fa-solid fa-thumbs-down mr-2"></i>Marcar como Perdida</button>
                            @endif
                        </x-kebab>
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

{{-- Modal de perda: justificativa obrigatória (EDUQ) --}}
<div x-show="perda" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" @click.self="perda = null">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-base font-semibold text-gray-800 mb-1">Marcar como Perdida</h3>
        <p class="text-xs text-gray-400 mb-4">A justificativa é obrigatória e alimenta o gráfico de motivos de perda do painel comercial.</p>
        <form method="POST" :action="perda || '#'" class="space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Motivo da perda <span class="text-red-500">*</span></label>
                <select name="motivo_perda_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Selecione...</option>
                    @foreach($motivosPerda ?? [] as $mp)
                    <option value="{{ $mp->id }}">{{ $mp->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" @click="perda = null" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg text-sm font-semibold">Confirmar Perda</button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
