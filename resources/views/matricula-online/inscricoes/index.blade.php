@extends('layouts.app')
@section('title', 'Acompanhamento de InscriÃ§Ãµes')

@php
$badges = [
    'pendente' => 'bg-amber-100 text-amber-700',
    'aprovada' => 'bg-blue-100 text-blue-700',
    'cancelada' => 'bg-red-100 text-red-700',
    'matriculada' => 'bg-green-100 text-green-700',
];
@endphp

@section('content')
<div class="bg-white rounded-xl border">
    <div class="p-5 border-b flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">149</span>
            <h1 class="text-lg font-semibold text-gray-800">Acompanhamento de InscriÃ§Ãµes</h1>
        </div>
        <a href="{{ route('matricula-online.inscricoes.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-700 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Nova InscriÃ§Ã£o
        </a>
    </div>

    {{-- Filtro por situaÃ§Ã£o --}}
    <div class="px-5 py-3 border-b flex gap-2 text-sm">
        @php $atual = request('situacao'); @endphp
        <a href="{{ route('matricula-online.inscricoes.index') }}" class="px-3 py-1 rounded-full {{ !$atual ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Todas</a>
        @foreach(['pendente','aprovada','matriculada','cancelada'] as $s)
        <a href="{{ route('matricula-online.inscricoes.index', ['situacao' => $s]) }}" class="px-3 py-1 rounded-full capitalize {{ $atual === $s ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">{{ $s }}</a>
        @endforeach
    </div>

    <div class="p-4">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Candidato</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Contato</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Abertura</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Pgto</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Contrato</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($inscricoes as $i)
                <tr class="hover:bg-gray-50" x-data="{ editing: false }">
                    <td class="px-4 py-3 text-gray-500">{{ $i->id }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $i->nome }}</td>
                    <td class="px-4 py-3 text-gray-600">
                        <div>{{ $i->email }}</div>
                        <div class="text-xs text-gray-400">{{ $i->telefone ?? '' }}</div>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $i->abertura?->nome ?? 'â€”' }}</td>
                    <td class="px-4 py-3">
                        <i class="fa-solid {{ $i->pagamento_confirmado ? 'fa-circle-check text-green-500' : 'fa-circle-xmark text-gray-300' }}"></i>
                    </td>
                    <td class="px-4 py-3">
                        <i class="fa-solid {{ $i->contrato_assinado ? 'fa-circle-check text-green-500' : 'fa-circle-xmark text-gray-300' }}"></i>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full capitalize {{ $badges[$i->situacao] ?? 'bg-gray-100 text-gray-500' }}">{{ $i->situacao }}</span>
                    </td>
                    <td class="px-4 py-3 relative">
                        <button @click="editing = !editing" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></button>
                        <form method="POST" action="{{ route('matricula-online.inscricoes.destroy', $i) }}" onsubmit="return confirm('Remover?')" class="inline">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>

                        {{-- Inline edit popover --}}
                        <div x-show="editing" x-cloak @click.away="editing = false" class="absolute right-4 top-12 z-20 bg-white border rounded-lg shadow-lg p-4 w-64 text-left">
                            <form method="POST" action="{{ route('matricula-online.inscricoes.update', $i) }}" class="space-y-3">
                                @csrf @method('PUT')
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">SituaÃ§Ã£o</label>
                                    <select name="situacao" class="w-full border rounded px-2 py-1.5 text-sm">
                                        @foreach(['pendente','aprovada','matriculada','cancelada'] as $s)
                                        <option value="{{ $s }}" {{ $i->situacao === $s ? 'selected' : '' }} class="capitalize">{{ ucfirst($s) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="flex items-center gap-2 text-sm text-gray-700">
                                    <input type="checkbox" name="pagamento_confirmado" value="1" {{ $i->pagamento_confirmado ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                                    Pagamento confirmado
                                </label>
                                <label class="flex items-center gap-2 text-sm text-gray-700">
                                    <input type="checkbox" name="contrato_assinado" value="1" {{ $i->contrato_assinado ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                                    Contrato assinado
                                </label>
                                <button type="submit" class="w-full bg-blue-600 text-white rounded py-1.5 text-sm font-medium hover:bg-blue-700">Salvar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Nenhuma inscriÃ§Ã£o encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $inscricoes->links() }}</div>
    </div>
</div>
<x-fab :route="route('matricula-online.inscricoes.create')" />
@endsection