@extends('layouts.app')
@section('title', 'Mensagens Enviadas')

@php
$badges = ['enviada' => 'bg-green-100 text-green-700', 'entregue' => 'bg-green-100 text-green-700', 'erro' => 'bg-red-100 text-red-700', 'pendente' => 'bg-amber-100 text-amber-700'];
@endphp

@section('content')
<div class="bg-white rounded-xl border">
    <div class="p-5 border-b flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">84</span>
            <div>
                <h1 class="text-lg font-semibold text-gray-800">Mensagens Avulsas</h1>
                <p class="text-xs text-gray-400">Comunicação › Mensagens</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('comunicacao.mensagens.avulsa') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-paper-plane mr-1"></i> Mensagem Avulsa</a>
            <a href="{{ route('comunicacao.mensagens.avisos') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50"><i class="fa-solid fa-bell mr-1"></i> Avisos Financeiros</a>
        </div>
    </div>
    <div class="p-4">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Operador</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Destinatário</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Observação</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Forma de Envio</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Data</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situação</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($mensagens as $m)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-700">{{ $m->enviadoPor?->nome ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-800">{{ $m->pessoa?->nome ?? $m->destinatario }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $m->assunto ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $m->forma_envio }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $m->created_at?->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3">
                        @php $sucesso = in_array($m->situacao, ['enviada','entregue']); @endphp
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $sucesso ? 'bg-green-100 text-green-700' : ($m->situacao==='erro' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">{{ $sucesso ? 'Sucesso' : ($m->situacao==='erro' ? 'Falha' : ucfirst($m->situacao)) }}</span>
                        @if($m->situacao === 'erro')<i class="fa-solid fa-circle-info text-red-400 ml-1" title="{{ $m->erro }}"></i>@endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhuma mensagem enviada.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $mensagens->links() }}</div>
    </div>
</div>
@endsection
