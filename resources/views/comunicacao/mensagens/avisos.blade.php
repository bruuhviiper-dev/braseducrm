@extends('layouts.app')
@section('title', 'Avisos Financeiros')

@section('content')
<div class="bg-white rounded-xl border">
    <div class="p-5 border-b flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">{{ $modo === 'cobranca' ? '88' : '86' }}</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $modo === 'cobranca' ? 'Aviso de Cobrança (vencidos)' : 'Aviso de Vencimento (próximos 7 dias)' }}</h1>
        </div>
        <div class="flex gap-2 text-sm">
            <a href="{{ route('comunicacao.mensagens.avisos', ['modo' => 'vencimento']) }}" class="px-3 py-1.5 rounded-full {{ $modo === 'vencimento' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">A vencer</a>
            <a href="{{ route('comunicacao.mensagens.avisos', ['modo' => 'cobranca']) }}" class="px-3 py-1.5 rounded-full {{ $modo === 'cobranca' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Vencidos</a>
        </div>
    </div>
    <div class="p-4">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Pessoa</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Documento</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Vencimento</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Valor</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Enviar aviso</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($titulos as $t)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $t->pessoa?->nome ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $t->numero_documento ?? $t->id }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $t->data_vencimento?->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-gray-800">R$ {{ number_format($t->valor_original, 2, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('comunicacao.mensagens.enviar-aviso', $t) }}" class="flex items-center gap-2">
                            @csrf
                            <select name="canal" class="border rounded px-2 py-1 text-xs">
                                <option value="email">E-mail</option>
                                <option value="sms">SMS</option>
                                <option value="whatsapp">WhatsApp</option>
                            </select>
                            <button class="px-2.5 py-1 bg-primary-600 text-white rounded text-xs hover:bg-primary-700"><i class="fa-solid fa-paper-plane"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhum título {{ $modo === 'cobranca' ? 'vencido' : 'a vencer nos próximos 7 dias' }}.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $titulos->links() }}</div>
    </div>
</div>
@endsection
