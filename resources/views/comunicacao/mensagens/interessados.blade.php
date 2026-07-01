@extends('layouts.app')
@section('title', 'Mensagens para Interessados CRM')

@section('content')
<div class="bg-white rounded-xl border" x-data="{ open: null }">
    <div class="p-5 border-b flex items-center gap-3">
        <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">62</span>
        <h1 class="text-lg font-semibold text-gray-800">Mensagens para Interessados CRM</h1>
    </div>
    @if(session('success'))<div class="mx-5 mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-2 rounded text-sm">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="mx-5 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded text-sm">{{ session('error') }}</div>@endif
    <div class="p-4">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Interessado</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Contato</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Enviar</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($interessados as $i)
                <tr class="hover:bg-gray-50 align-top">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $i->nome }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $i->email ?? $i->celular ?? $i->telefone ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <button @click="open = (open === {{ $i->id }} ? null : {{ $i->id }})" class="px-2.5 py-1 bg-primary-600 text-white rounded text-xs hover:bg-primary-700"><i class="fa-solid fa-paper-plane mr-1"></i> Mensagem</button>
                        <div x-show="open === {{ $i->id }}" x-cloak class="mt-2 p-3 bg-gray-50 border rounded-lg" style="min-width:320px">
                            <form method="POST" action="{{ route('comunicacao.mensagens.enviar-interessado') }}" class="space-y-2">
                                @csrf
                                <input type="hidden" name="interessado_id" value="{{ $i->id }}">
                                <div class="flex gap-2">
                                    <select name="canal" class="border rounded px-2 py-1.5 text-xs">
                                        <option value="email">E-mail</option>
                                        <option value="sms">SMS</option>
                                        <option value="whatsapp">WhatsApp</option>
                                    </select>
                                    <input type="text" name="assunto" placeholder="Assunto" class="flex-1 border rounded px-2 py-1.5 text-xs">
                                </div>
                                <textarea name="conteudo" rows="2" required placeholder="Mensagem..." class="w-full border rounded px-2 py-1.5 text-xs"></textarea>
                                <button class="w-full px-2 py-1.5 bg-primary-600 text-white rounded text-xs font-medium hover:bg-primary-700">Enviar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-4 py-8 text-center text-gray-400">Nenhum interessado ativo.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $interessados->links() }}</div>
    </div>
</div>
@endsection
