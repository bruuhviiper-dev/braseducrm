@extends('layouts.app')
@section('title', 'Controle de Indicações')

@section('content')
<div class="space-y-4">
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-white rounded-xl border p-4"><p class="text-xs text-gray-500 uppercase">Total</p><p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p></div>
        <div class="bg-white rounded-xl border p-4"><p class="text-xs text-gray-500 uppercase">Pendentes</p><p class="text-2xl font-bold text-amber-600">{{ $stats['pendentes'] }}</p></div>
        <div class="bg-white rounded-xl border p-4"><p class="text-xs text-gray-500 uppercase">Convertidas</p><p class="text-2xl font-bold text-green-600">{{ $stats['convertidas'] }}</p></div>
    </div>

    <x-data-table title="Controle de Indicações" codigo="223" :createRoute="route('geral.indicacoes.create')">
        <form method="GET" class="flex gap-2 mb-4">
            <select name="situacao" onchange="this.form.submit()" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">Todos os status</option>
                @foreach(\App\Models\Indicacao::STATUS as $k => $v)<option value="{{ $k }}" @selected(request('situacao')==$k)>{{ $v }}</option>@endforeach
            </select>
        </form>
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Indicador (aluno)</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Indicado</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Campanha</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($indicacoes as $i)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $i->aluno?->pessoa?->nome ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-800">{{ $i->nome_indicado }}<span class="block text-xs text-gray-400">{{ $i->telefone_indicado ?? $i->email_indicado }}</span></td>
                    <td class="px-4 py-3 text-gray-600">{{ $i->campanha?->nome ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('geral.indicacoes.status', $i) }}">
                            @csrf
                            @php $cor = ['pendente'=>'text-amber-700 bg-amber-50','convertido'=>'text-green-700 bg-green-50','nao_convertido'=>'text-red-700 bg-red-50'][$i->situacao] ?? ''; @endphp
                            <select name="situacao" onchange="this.form.submit()" class="border-0 rounded px-2 py-1 text-xs {{ $cor }}">
                                @foreach(\App\Models\Indicacao::STATUS as $k => $v)<option value="{{ $k }}" @selected($i->situacao==$k)>{{ $v }}</option>@endforeach
                            </select>
                        </form>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-1">
                            <a href="{{ route('geral.indicacoes.edit', $i) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                            <form method="POST" action="{{ route('geral.indicacoes.destroy', $i) }}" onsubmit="return confirm('Remover?')">
                                @csrf @method('DELETE')
                                <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhuma indicação registrada.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $indicacoes->links() }}</div>
    </x-data-table>
</div>
@endsection
