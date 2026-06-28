@extends('layouts.app')
@section('title', 'Configuração do CRM')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center gap-3 px-6 py-4 border-b">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">166</span>
            <h2 class="text-base font-semibold text-gray-800">Configuração do CRM</h2>
        </div>
        <form method="POST" action="{{ route('crm.configuracoes.update') }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Distribuição e Automação</h3>
                <div class="space-y-4">
                    <label class="flex items-start gap-3">
                        <input type="checkbox" name="roleta_ativa" value="1" {{ old('roleta_ativa', $config->roleta_ativa) ? 'checked' : '' }} class="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span>
                            <span class="block text-sm font-medium text-gray-700">Roleta de distribuição automática</span>
                            <span class="block text-xs text-gray-400">Distribui novos interessados entre os consultores automaticamente.</span>
                        </span>
                    </label>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dias para perda automática</label>
                        <input type="number" min="0" name="dias_perda_automatica" value="{{ old('dias_perda_automatica', $config->dias_perda_automatica) }}" placeholder="Ex.: 30" class="w-40 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-400 mt-1">Oportunidades sem atividade por este período são marcadas como perdidas. Deixe vazio para desativar.</p>
                    </div>
                </div>
            </div>

            <div class="border-t pt-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Integração RD Station</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Token de API</label>
                        <input type="text" name="rd_station_token" value="{{ old('rd_station_token', $config->rd_station_token) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL do Webhook</label>
                        <input type="text" name="rd_station_url" value="{{ old('rd_station_url', $config->rd_station_url) }}" placeholder="https://..." class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-2 border-t">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 mt-4">
                    Salvar Configurações
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
