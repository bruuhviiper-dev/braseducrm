@extends('layouts.app')
@section('title', 'Cupons Personalizados')

@section('content')
<x-data-table title="Cupons Personalizados" codigo="193" :createRoute="route('matricula-online.cupons-personalizados.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Código</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Beneficiário</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Desconto</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Validade</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situação</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($cupons as $c)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono font-medium text-gray-800">{{ $c->codigo }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $c->beneficiario ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-800">{{ $c->tipo_desconto === 'percentual' ? number_format($c->valor_desconto, 0).'%' : 'R$ '.number_format($c->valor_desconto, 2, ',', '.') }}</td>
                <td class="px-4 py-3 text-gray-600">{{ optional($c->validade)->format('d/m/Y') ?? '—' }}</td>
                <td class="px-4 py-3">
                    @if($c->usado)<span class="px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-600">Usado</span>
                    @elseif(!$c->ativo)<span class="px-2 py-0.5 rounded text-xs bg-red-100 text-red-700">Inativo</span>
                    @else<span class="px-2 py-0.5 rounded text-xs bg-green-100 text-green-700">Disponível</span>@endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('matricula-online.cupons-personalizados.edit', $c) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('matricula-online.cupons-personalizados.destroy', $c) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhum cupom personalizado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $cupons->links() }}</div>
</x-data-table>
@endsection
