@extends('layouts.app')
@section('title', 'Conciliação de Recebimentos (Cartão)')

@section('content')
<div class="space-y-4">
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-white rounded-xl border p-4">
            <p class="text-xs text-gray-500 uppercase">Bruto total</p>
            <p class="text-xl font-bold text-gray-800">R$ {{ number_format($totais['bruto'], 2, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-xs text-gray-500 uppercase">Líquido total</p>
            <p class="text-xl font-bold text-green-600">R$ {{ number_format($totais['liquido'], 2, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-xs text-gray-500 uppercase">A conciliar (líquido)</p>
            <p class="text-xl font-bold text-amber-600">R$ {{ number_format($totais['pendente'], 2, ',', '.') }}</p>
        </div>
    </div>

    <x-data-table title="Conciliação de Recebimentos (Cartão)" codigo="71" :createRoute="route('financeiro.conciliacao-cartao.create')">
        <form method="GET" class="flex flex-wrap gap-2 mb-4">
            <select name="contrato_cartao_id" onchange="this.form.submit()" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">Todas as operadoras</option>
                @foreach($contratos as $ct)<option value="{{ $ct->id }}" @selected(request('contrato_cartao_id')==$ct->id)>{{ $ct->operadora }}</option>@endforeach
            </select>
            <select name="conciliado" onchange="this.form.submit()" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">Todos</option>
                <option value="0" @selected(request('conciliado')==='0')>Pendentes</option>
                <option value="1" @selected(request('conciliado')==='1')>Conciliados</option>
            </select>
            @if(request('contrato_cartao_id') || request('conciliado')!==null && request('conciliado')!=='')<a href="{{ route('financeiro.conciliacao-cartao.index') }}" class="px-3 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Limpar</a>@endif
        </form>

        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                <th class="py-3 px-3 w-10"></th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Venda</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Operadora</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Modalidade</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Bruto</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Taxa</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Líquido</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Previsão</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($recebimentos as $r)
                <tr class="hover:bg-gray-50">
                <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $r->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                    <td class="px-4 py-3 text-gray-600">{{ optional($r->data_venda)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-gray-800">{{ $r->contrato?->operadora ?? '—' }}<span class="block text-xs text-gray-400">{{ $r->bandeira }}</span></td>
                    <td class="px-4 py-3 text-gray-600">{{ \App\Models\RecebimentoCartao::MODALIDADES[$r->modalidade] ?? $r->modalidade }}{{ $r->modalidade==='credito_parcelado' ? ' '.$r->parcelas.'x' : '' }}</td>
                    <td class="px-4 py-3 text-gray-800">R$ {{ number_format($r->valor_bruto, 2, ',', '.') }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ number_format($r->taxa_aplicada, 2, ',', '.') }}%</td>
                    <td class="px-4 py-3 font-medium text-green-700">R$ {{ number_format($r->valor_liquido, 2, ',', '.') }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ optional($r->previsao_recebimento)->format('d/m/Y') ?? '—' }}</td>
                    <td class="px-4 py-3">
                        @if($r->conciliado)
                        <span class="px-2 py-0.5 rounded text-xs bg-green-100 text-green-700">Conciliado</span>
                        @else
                        <span class="px-2 py-0.5 rounded text-xs bg-amber-100 text-amber-700">Pendente</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <x-kebab :delete="route('financeiro.conciliacao-cartao.destroy', $r)"><form method="POST" action="{{ route('financeiro.conciliacao-cartao.conciliar', $r) }}">
                                @csrf
                                <button class="p-1.5 {{ $r->conciliado ? 'text-amber-600 hover:bg-amber-50' : 'text-green-600 hover:bg-green-50' }} rounded" title="{{ $r->conciliado ? 'Desfazer conciliação' : 'Conciliar' }}">
                                    <i class="fa-solid {{ $r->conciliado ? 'fa-rotate-left' : 'fa-check-double' }}"></i>
                                </button>
                            </form></x-kebab>
                        </td>
                </tr>
                @empty
                <tr><td colspan="10" class="px-4 py-8 text-center text-gray-400">Nenhum recebimento lançado.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $recebimentos->links() }}</div>
    </x-data-table>
</div>
@endsection
