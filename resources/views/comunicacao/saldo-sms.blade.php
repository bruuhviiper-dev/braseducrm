@extends('layouts.app')
@section('title', 'Consulta de Saldo SMS')

@section('content')
<div class="max-w-3xl mx-auto space-y-4">
    <div class="bg-white rounded-xl border p-5 flex items-center gap-3">
        <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">89</span>
        <h1 class="text-lg font-semibold text-gray-800">Consulta de Saldo SMS</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="bg-white rounded-xl border p-4">
            <p class="text-xs text-gray-500 uppercase">Integração SMS</p>
            <p class="text-lg font-bold {{ $integracao && $integracao->ativo ? 'text-green-600' : 'text-gray-400' }}">{{ $integracao && $integracao->ativo ? 'Ativa' : 'Inativa' }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-xs text-gray-500 uppercase">SMS enviados (mês)</p>
            <p class="text-2xl font-bold text-gray-800">{{ $enviadosMes }}</p>
        </div>
        <div class="bg-white rounded-xl border p-4">
            <p class="text-xs text-gray-500 uppercase">SMS enviados (total)</p>
            <p class="text-2xl font-bold text-gray-800">{{ $enviados }}</p>
        </div>
    </div>

    @if(!$integracao || !$integracao->ativo)
    <div class="bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded text-sm">
        A integração de SMS não está configurada. Configure em <a href="{{ Route::has('integracoes.index') ? route('integracoes.index') : '#' }}" class="underline font-medium">Integrações</a> para consultar o saldo real do provedor.
    </div>
    @endif

    <div class="bg-white rounded-xl border">
        <div class="px-5 py-3 border-b"><h2 class="text-sm font-semibold text-gray-700">Últimos SMS enviados</h2></div>
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Destinatário</th>
                    <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Pessoa</th>
                    <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Data</th>
                    <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Situação</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($ultimos as $m)
                <tr>
                    <td class="px-4 py-2 text-gray-800">{{ $m->destinatario }}</td>
                    <td class="px-4 py-2 text-gray-600">{{ $m->pessoa?->nome ?? '—' }}</td>
                    <td class="px-4 py-2 text-gray-600">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-2"><span class="px-2 py-0.5 rounded text-xs {{ $m->situacao === 'enviada' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($m->situacao) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhum SMS enviado ainda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
