@extends('layouts.app')
@section('title', 'Emissão de Propostas')

@section('content')
<div class="bg-white rounded-xl border">
    <div class="p-5 border-b flex items-center gap-3">
        <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">201</span>
        <h1 class="text-lg font-semibold text-gray-800">Emissão de Propostas (CRM)</h1>
    </div>
    <div class="p-4">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Oportunidade</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Interessado</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Produto/Serviço</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Valor</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Proposta</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($oportunidades as $o)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $o->titulo }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $o->interessado?->nome ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $o->produtoServico?->nome ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-800">R$ {{ number_format($o->valor, 2, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('crm.propostas.gerar', $o) }}" target="_blank" class="px-3 py-1.5 bg-red-600 text-white rounded-lg text-xs font-medium hover:bg-red-700 inline-flex items-center gap-1"><i class="fa-solid fa-file-pdf"></i> Gerar PDF</a>
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
