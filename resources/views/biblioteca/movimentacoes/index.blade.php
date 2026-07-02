@extends('layouts.app')
@section('title', 'Movimentações de Exemplares')

@section('content')
<x-data-table title="Movimentações de Exemplares" codigo="287" :createRoute="route('biblioteca.movimentacoes.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Exemplar</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Pessoa</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Empréstimo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Prev. Devolução</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Multa</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Operador</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($movimentacoes as $m)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $m->exemplar?->obra?->titulo ?? '—' }} @if($m->renovacoes)<span class="text-xs text-gray-400">(renov. {{ $m->renovacoes }})</span>@endif</td>
                <td class="px-4 py-3 text-gray-600">{{ $m->pessoa?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ optional($m->data_emprestimo)->format('d/m/Y') }}</td>
                <td class="px-4 py-3 text-gray-600">{{ optional($m->data_prevista_devolucao)->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                    @php $cor = ['Empréstimo'=>'bg-amber-100 text-amber-700','Renovação'=>'bg-blue-100 text-blue-700','Devolução'=>'bg-green-100 text-green-700'][$m->tipo]; @endphp
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $cor }}">{{ $m->tipo }}</span>
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $m->multa > 0 ? 'R$ '.number_format($m->multa, 2, ',', '.') : '—' }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $m->operador?->nome ?? '—' }}</td>
                <td class="px-4 py-3">
                    <div class="flex gap-1 items-center">
                        @if($m->situacao === 'emprestado')
                        <form method="POST" action="{{ route('biblioteca.movimentacoes.renovar', $m) }}">
                            @csrf @method('PUT')
                            <button class="px-2.5 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700"><i class="fa-solid fa-arrows-rotate mr-1"></i> Renovar</button>
                        </form>
                        <form method="POST" action="{{ route('biblioteca.movimentacoes.devolver', $m) }}">
                            @csrf @method('PUT')
                            <button class="px-2.5 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700"><i class="fa-solid fa-rotate-left mr-1"></i> Devolver</button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('biblioteca.movimentacoes.destroy', $m) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Nenhuma movimentação.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $movimentacoes->links() }}</div>
</x-data-table>
@endsection
