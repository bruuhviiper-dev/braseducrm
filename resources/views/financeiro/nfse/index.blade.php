@extends('layouts.app')
@section('title', 'Configurações de NFS-e')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">227</span>
            <h1 class="text-lg font-semibold text-gray-800">Configurações de NFS-e</h1>
        </div>
        @if(session('success'))
        <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-2 rounded text-sm">{{ session('success') }}</div>
        @endif
        <form action="{{ route('financeiro.nfse.update') }}" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ambiente <span class="text-red-500">*</span></label>
                    <select name="ambiente" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        @foreach(\App\Models\ConfiguracaoNfse::AMBIENTES as $k => $v)<option value="{{ $k }}" @selected(old('ambiente', $config->ambiente)==$k)>{{ $v }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Regime Tributário</label>
                    <select name="regime_tributario" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach(\App\Models\ConfiguracaoNfse::REGIMES as $k => $v)<option value="{{ $k }}" @selected(old('regime_tributario', $config->regime_tributario)==$k)>{{ $v }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Inscrição Municipal</label>
                    <input type="text" name="inscricao_municipal" value="{{ old('inscricao_municipal', $config->inscricao_municipal) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Código do Serviço</label>
                    <input type="text" name="codigo_servico" value="{{ old('codigo_servico', $config->codigo_servico) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Série RPS</label>
                    <input type="text" name="serie_rps" value="{{ old('serie_rps', $config->serie_rps) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número RPS atual</label>
                    <input type="number" min="1" name="numero_rps_atual" value="{{ old('numero_rps_atual', $config->numero_rps_atual) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alíquota ISS (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="aliquota_iss" value="{{ old('aliquota_iss', $config->aliquota_iss) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2 text-sm pb-2">
                        <input type="checkbox" name="iss_retido" value="1" {{ old('iss_retido', $config->iss_retido) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600"> ISS retido na fonte
                    </label>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Discriminação padrão do serviço</label>
                <textarea name="discriminacao_padrao" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('discriminacao_padrao', $config->discriminacao_padrao) }}</textarea>
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="ativo" value="1" {{ old('ativo', $config->ativo) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600"> Emissão de NFS-e ativa
            </label>

            <div class="flex justify-end pt-2 border-t">
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar Configurações</button>
            </div>
        </form>
    </div>
</div>
@endsection
