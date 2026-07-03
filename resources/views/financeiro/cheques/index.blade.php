@extends('layouts.app')
@section('title', 'Manutenção de Cheques')

@section('content')
<div class="space-y-4">
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-white rounded-xl border p-4">
            <p class="text-xs text-gray-500 uppercase">Em carteira</p>
            <p class="text-2xl font-bold text-gray-800">R$ {{ number_format($totais['carteira'], 2, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-xs text-gray-500 uppercase">Devolvidos</p>
            <p class="text-2xl font-bold text-red-600">R$ {{ number_format($totais['devolvido'], 2, ',', '.') }}</p>
        </div>
    </div>

    <x-data-table title="Manutenção de Cheques" codigo="72" :createRoute="route('financeiro.cheques.create')">
        <form method="GET" class="flex flex-wrap gap-2 mb-4">
            <select name="tipo" onchange="this.form.submit()" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">Todos os tipos</option>
                @foreach(\App\Models\Cheque::TIPOS as $k => $v)<option value="{{ $k }}" @selected(request('tipo')==$k)>{{ $v }}</option>@endforeach
            </select>
            <select name="situacao" onchange="this.form.submit()" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">Todas as situações</option>
                @foreach(\App\Models\Cheque::SITUACOES as $k => $v)<option value="{{ $k }}" @selected(request('situacao')==$k)>{{ $v }}</option>@endforeach
            </select>
            @if(request('tipo') || request('situacao'))<a href="{{ route('financeiro.cheques.index') }}" class="px-3 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Limpar</a>@endif
        </form>

        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                <th class="py-3 px-3 w-10"></th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nº / Banco</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Emitente</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Bom para</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Valor</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situação</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($cheques as $c)
                <tr class="hover:bg-gray-50" x-data="{ open: false }">
                <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $c->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $c->numero }}<span class="block text-xs text-gray-400">{{ $c->banco?->nome ?? '—' }}</span></td>
                    <td class="px-4 py-3 text-gray-600">{{ $c->emitente ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ \App\Models\Cheque::TIPOS[$c->tipo] ?? $c->tipo }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ optional($c->bom_para)->format('d/m/Y') ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-800">R$ {{ number_format($c->valor, 2, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        @php $cor = ['carteira'=>'bg-gray-100 text-gray-700','depositado'=>'bg-blue-100 text-blue-700','compensado'=>'bg-green-100 text-green-700','devolvido'=>'bg-red-100 text-red-700','repassado'=>'bg-amber-100 text-amber-700'][$c->situacao] ?? 'bg-gray-100 text-gray-700'; @endphp
                        <span class="px-2 py-0.5 rounded text-xs {{ $cor }}">{{ \App\Models\Cheque::SITUACOES[$c->situacao] ?? $c->situacao }}</span>
                        @if($c->situacao === 'devolvido' && $c->motivoDevolucao)<span class="block text-xs text-gray-400 mt-0.5">{{ $c->motivoDevolucao->nome }}</span>@endif
                    </td>
                    <td class="px-4 py-3">
                        <x-kebab :edit="route('financeiro.cheques.edit', $c)" :delete="route('financeiro.cheques.destroy', $c)"><button @click="open = !open" class="p-1.5 text-gray-600 hover:bg-gray-100 rounded" title="Alterar situação"><i class="fa-solid fa-arrows-rotate"></i></button>
                            
                            
                        </div>
                        <div x-show="open" x-cloak class="mt-2 p-2 bg-gray-50 border rounded-lg" style="min-width:260px">
                            <form method="POST" action="{{ route('financeiro.cheques.situacao', $c) }}" class="space-y-2" x-data="{ sit: '{{ $c->situacao }}' }">
                                @csrf
                                <select name="situacao" x-model="sit" class="w-full border rounded px-2 py-1.5 text-xs">
                                    @foreach(\App\Models\Cheque::SITUACOES as $k => $v)<option value="{{ $k }}">{{ $v }}</option>@endforeach
                                </select>
                                <select name="motivo_devolucao_id" x-show="sit === 'devolvido'" class="w-full border rounded px-2 py-1.5 text-xs">
                                    <option value="">Motivo da devolução...</option>
                                    @foreach($motivos ?? \App\Models\MotivoDevolucaoCheque::where('ativo',true)->orderBy('nome')->get() as $m)<option value="{{ $m->id }}">{{ $m->nome }}</option>@endforeach
                                </select>
                                <button class="w-full px-2 py-1.5 bg-primary-600 text-white rounded text-xs font-medium hover:bg-primary-700">Aplicar</button>
                            </form></x-kebab>
                        </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Nenhum cheque cadastrado.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $cheques->links() }}</div>
    </x-data-table>
</div>
@endsection
