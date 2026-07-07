@extends('layouts.app')
@section('title', 'Emissão de Propostas')

@section('content')
<div class="bg-white">
    <div class="p-5 border-b flex items-center gap-3">
        <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">201</span>
        <h1 class="text-lg font-semibold text-gray-800">Emissão de Propostas (CRM)</h1>
    </div>
    @if(session('error'))
    <div class="mx-4 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">{{ session('error') }}</div>
    @endif
    @if($errors->any())
    <div class="mx-4 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif
    <div class="p-4">
        <p class="text-xs text-gray-400 mb-3">Alçada de aprovação: proposta com desconto acima do limite do operador (Cadastro de Operador) fica pendente até o gestor aprovar — o PDF só é liberado depois.</p>
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Oportunidade</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Interessado</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Valor</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Proposta / Alçada</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($oportunidades as $o)
                @php $ultima = ($propostas[$o->id] ?? collect())->first(); @endphp
                <tr class="hover:bg-gray-50 align-top">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $o->titulo ?: 'Oportunidade #'.$o->id }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $o->interessado?->nome ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-800">R$ {{ number_format($o->valor, 2, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        @if($ultima)
                            <div class="text-xs text-gray-600">
                                R$ {{ number_format($ultima->valor, 2, ',', '.') }}
                                @if($ultima->desconto_percentual) · desc. {{ number_format($ultima->desconto_percentual, 1, ',', '.') }}% @endif
                            </div>
                            @if($ultima->aprovacao === 'pendente')
                                <span class="text-xs font-semibold text-yellow-700 bg-yellow-100 px-2 py-0.5 rounded-full">Aguardando aprovação do gestor</span>
                            @elseif($ultima->aprovacao === 'aprovada')
                                <span class="text-xs font-semibold text-green-700 bg-green-100 px-2 py-0.5 rounded-full">Aprovada por {{ $ultima->aprovadaPor?->nome }}</span>
                            @elseif($ultima->aprovacao === 'reprovada')
                                <span class="text-xs font-semibold text-red-700 bg-red-100 px-2 py-0.5 rounded-full" title="{{ $ultima->motivo_reprovacao }}">Reprovada</span>
                            @else
                                <span class="text-xs text-gray-400">Dentro da alçada</span>
                            @endif
                        @else
                            <span class="text-xs text-gray-400">Sem proposta formal</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 space-y-1.5" x-data="{ nova: false }">
                        @if($ultima && $ultima->aprovacao === 'pendente' && (auth()->user()->limite_desconto === null || auth()->user()->is_admin))
                            <form method="POST" action="{{ route('crm.propostas.aprovar', $ultima) }}" class="flex items-center gap-1">
                                @csrf
                                <button name="decisao" value="aprovada" class="px-2 py-1 bg-green-600 text-white rounded text-xs font-medium hover:bg-green-700">Aprovar</button>
                                <input type="text" name="motivo_reprovacao" placeholder="motivo p/ reprovar" class="w-32 border rounded px-2 py-1 text-xs">
                                <button name="decisao" value="reprovada" class="px-2 py-1 bg-red-600 text-white rounded text-xs font-medium hover:bg-red-700">Reprovar</button>
                            </form>
                        @endif
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('crm.propostas.gerar', $o) }}" target="_blank" class="px-3 py-1.5 bg-red-600 text-white rounded-lg text-xs font-medium hover:bg-red-700 inline-flex items-center gap-1"><i class="fa-solid fa-file-pdf"></i> PDF</a>
                            <button type="button" @click="nova = !nova" class="px-3 py-1.5 border border-cyan-300 text-cyan-700 rounded-lg text-xs font-medium hover:bg-cyan-50">Nova proposta</button>
                        </div>
                        <form x-show="nova" x-cloak method="POST" action="{{ route('crm.propostas.store', $o) }}" class="flex items-center gap-1 pt-1">
                            @csrf
                            <input type="number" step="0.01" min="0" name="valor" value="{{ $o->valor }}" placeholder="Valor" class="w-24 border rounded px-2 py-1 text-xs" required>
                            <input type="number" step="0.01" min="0" max="100" name="desconto_percentual" placeholder="% desc." class="w-20 border rounded px-2 py-1 text-xs">
                            <button class="px-2 py-1 bg-primary-600 text-white rounded text-xs font-medium hover:bg-primary-700">Criar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhuma oportunidade cadastrada.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $oportunidades->links() }}</div>
    </div>
</div>
@endsection
