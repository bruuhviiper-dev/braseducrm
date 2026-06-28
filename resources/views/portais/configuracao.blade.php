@extends('layouts.app')
@section('title', 'Configuração do Portal do Aluno')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center gap-3 px-6 py-4 border-b">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">46</span>
            <h2 class="text-base font-semibold text-gray-800">Configuração do Portal do Aluno</h2>
        </div>
        <form method="POST" action="{{ route('portais.configuracao.salvar') }}" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome do Portal <span class="text-red-500">*</span></label>
                    <input type="text" name="nome_portal" value="{{ old('nome_portal', $config->nome_portal) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cor Primária <span class="text-red-500">*</span></label>
                    <input type="color" name="cor_primaria" value="{{ old('cor_primaria', $config->cor_primaria) }}" class="w-20 h-10 border rounded-lg cursor-pointer">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mensagem de Boas-vindas</label>
                <textarea name="mensagem_boas_vindas" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('mensagem_boas_vindas', $config->mensagem_boas_vindas) }}</textarea>
            </div>

            <div class="border-t pt-4">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Seções visíveis no portal</h3>
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="exibe_financeiro" value="1" {{ old('exibe_financeiro', $config->exibe_financeiro) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600"> Financeiro (boletos e títulos)
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="exibe_boletim" value="1" {{ old('exibe_boletim', $config->exibe_boletim) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600"> Boletim (notas e frequência)
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="exibe_documentos" value="1" {{ old('exibe_documentos', $config->exibe_documentos) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600"> Documentos
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-2 border-t pt-4">
                <input type="checkbox" name="ativo" value="1" id="ativo" {{ old('ativo', $config->ativo) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="ativo" class="text-sm text-gray-700">Portal ativo</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">Salvar Configuração</button>
                <a href="{{ route('portais.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
