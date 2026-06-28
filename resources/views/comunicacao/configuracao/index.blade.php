@extends('layouts.app')
@section('title', 'Configuração da Comunicação')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center gap-3 px-6 py-4 border-b">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">85</span>
            <h2 class="text-base font-semibold text-gray-800">Configuração da Comunicação</h2>
        </div>
        <form method="POST" action="{{ route('comunicacao.configuracao.index') }}" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Remetente (nome)</label>
                    <input type="text" name="remetente_nome" value="{{ old('remetente_nome', $config->remetente_nome) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Remetente (e-mail)</label>
                    <input type="email" name="remetente_email" value="{{ old('remetente_email', $config->remetente_email) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Canal padrão</label>
                <select name="canal_padrao" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="email" {{ old('canal_padrao', $config->canal_padrao) == 'email' ? 'selected' : '' }}>E-mail</option>
                    <option value="sms" {{ old('canal_padrao', $config->canal_padrao) == 'sms' ? 'selected' : '' }}>SMS</option>
                    <option value="whatsapp" {{ old('canal_padrao', $config->canal_padrao) == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assinatura padrão</label>
                <textarea name="assinatura" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('assinatura', $config->assinatura) }}</textarea>
            </div>

            <div class="border-t pt-4 space-y-3">
                <label class="flex items-center gap-3">
                    <input type="checkbox" name="enviar_aviso_vencimento" value="1" {{ old('enviar_aviso_vencimento', $config->enviar_aviso_vencimento) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                    <span class="text-sm text-gray-700">Enviar aviso de vencimento</span>
                </label>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dias antes do vencimento para avisar</label>
                    <input type="number" min="0" name="dias_aviso_vencimento" value="{{ old('dias_aviso_vencimento', $config->dias_aviso_vencimento) }}" class="w-32 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <label class="flex items-center gap-3">
                    <input type="checkbox" name="enviar_aviso_cobranca" value="1" {{ old('enviar_aviso_cobranca', $config->enviar_aviso_cobranca) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                    <span class="text-sm text-gray-700">Enviar aviso de cobrança (após vencimento)</span>
                </label>
            </div>

            <div class="border-t pt-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">Salvar Configuração</button>
            </div>
        </form>
    </div>
</div>
@endsection
