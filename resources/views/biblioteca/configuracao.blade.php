@extends('layouts.app')
@section('title', 'Configuração do Biblioteca')

@section('content')
<div class="max-w-3xl mx-auto" x-data="{ multa: {{ $config->aplicar_multa ? 'true' : 'false' }} }">
    <form action="{{ route('biblioteca.configuracao.update') }}" method="POST" class="space-y-4">
        @csrf @method('PUT')

        @if(session('success'))<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-2 rounded text-sm">{{ session('success') }}</div>@endif
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
            <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <div class="bg-white rounded-xl border">
            <div class="px-6 py-4 border-b flex items-center gap-3">
                <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">299</span>
                <h1 class="text-lg font-semibold text-gray-800">Configuração do Biblioteca</h1>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">Configurações Gerais</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @php $campos = [
                            'max_emprestimos' => 'Qtd. máxima de empréstimos',
                            'dias_devolucao' => 'Qtd. de dias para devolução',
                            'max_renovacoes' => 'Qtd. máxima de renovações',
                            'dias_reserva' => 'Qtd. de dias para reserva',
                            'max_reservas' => 'Qtd. máxima de reservas',
                        ]; @endphp
                        @foreach($campos as $name => $label)
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">{{ $label }} <span class="text-red-500">*</span></label>
                            <input type="number" min="0" name="{{ $name }}" value="{{ old($name, $config->$name) }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="border-t pt-5">
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">Financeiro (multa)</h2>
                    <label class="flex items-center gap-2 cursor-pointer mb-3">
                        <input type="checkbox" name="aplicar_multa" value="1" x-model="multa" {{ $config->aplicar_multa ? 'checked' : '' }} class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="text-sm font-medium text-gray-700">Aplicar multa diária?</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-show="multa" x-cloak>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Valor diário (R$)</label>
                            <input type="number" step="0.01" min="0" name="valor_diario" value="{{ old('valor_diario', $config->valor_diario) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Categoria do Título</label>
                            <input type="text" name="categoria_titulo" value="{{ old('categoria_titulo', $config->categoria_titulo) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Forma de Pagamento</label>
                            <input type="text" name="forma_pagamento" value="{{ old('forma_pagamento', $config->forma_pagamento) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t flex justify-end">
                <button type="submit" class="px-5 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </div>
    </form>
</div>
@endsection
