@extends('layouts.app')
@section('title', 'Emissões Financeiras')

@section('content')
<div class="space-y-4">
    <div class="bg-white rounded-xl border p-5">
        <h1 class="text-lg font-semibold text-gray-800 mb-1">Emissões Financeiras</h1>
        <p class="text-sm text-gray-500">Gere os relatórios financeiros em PDF.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- 173 Títulos a Pagar --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">173</span><h2 class="font-semibold text-gray-800">Títulos a Pagar</h2></div>
            <form method="GET" action="{{ route('financeiro.emissoes.titulos-pagar') }}" target="_blank" class="flex gap-2 items-end">
                <div class="flex-1">
                    <label class="block text-xs text-gray-500 mb-1">Situação</label>
                    <select name="situacao" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Todas</option>
                        @foreach(['aberto','pago','cancelado'] as $s)<option value="{{ $s }}">{{ ucfirst($s) }}</option>@endforeach
                    </select>
                </div>
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700"><i class="fa-solid fa-file-pdf mr-1"></i> PDF</button>
            </form>
        </div>

        {{-- 66 Boletos --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">66</span><h2 class="font-semibold text-gray-800">Boletos Bancários</h2></div>
            <a href="{{ route('financeiro.emissoes.boletos') }}" target="_blank" class="inline-block px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700"><i class="fa-solid fa-file-pdf mr-1"></i> Gerar PDF</a>
        </div>

        {{-- 113 Cobrança --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">113</span><h2 class="font-semibold text-gray-800">Cobrança (vencidos)</h2></div>
            <a href="{{ route('financeiro.emissoes.cobranca') }}" target="_blank" class="inline-block px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700"><i class="fa-solid fa-file-pdf mr-1"></i> Gerar PDF</a>
        </div>

        {{-- 106 Fechamento de Caixa --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">106</span><h2 class="font-semibold text-gray-800">Fechamento de Caixa</h2></div>
            <form method="GET" action="{{ route('financeiro.emissoes.fechamento-caixa') }}" target="_blank" class="flex gap-2 items-end">
                <div class="flex-1">
                    <label class="block text-xs text-gray-500 mb-1">Caixa <span class="text-red-500">*</span></label>
                    <select name="caixa_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($caixas as $cx)<option value="{{ $cx->id }}">Caixa #{{ $cx->id }} — {{ optional($cx->data_fechamento)->format('d/m/Y') }}</option>@endforeach
                    </select>
                </div>
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700"><i class="fa-solid fa-file-pdf mr-1"></i> PDF</button>
            </form>
        </div>

        {{-- 93 Conta Corrente por Pessoa --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">93</span><h2 class="font-semibold text-gray-800">Conta Corrente por Pessoa</h2></div>
            <form method="GET" action="{{ route('financeiro.emissoes.conta-corrente') }}" target="_blank" class="flex gap-2 items-end">
                <div class="flex-1">
                    <label class="block text-xs text-gray-500 mb-1">Pessoa <span class="text-red-500">*</span></label>
                    <select name="pessoa_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($pessoas as $p)<option value="{{ $p->id }}">{{ $p->nome }}</option>@endforeach
                    </select>
                </div>
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700"><i class="fa-solid fa-file-pdf mr-1"></i> PDF</button>
            </form>
        </div>

        {{-- 101 Resumo Financeiro da Pessoa --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">101</span><h2 class="font-semibold text-gray-800">Resumo Financeiro da Pessoa</h2></div>
            <form method="GET" action="{{ route('financeiro.emissoes.resumo-pessoa') }}" target="_blank" class="flex gap-2 items-end">
                <div class="flex-1">
                    <label class="block text-xs text-gray-500 mb-1">Pessoa <span class="text-red-500">*</span></label>
                    <select name="pessoa_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($pessoas as $p)<option value="{{ $p->id }}">{{ $p->nome }}</option>@endforeach
                    </select>
                </div>
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700"><i class="fa-solid fa-file-pdf mr-1"></i> PDF</button>
            </form>
        </div>

        {{-- 180 Comissões --}}
        <div class="bg-white rounded-xl border p-5 md:col-span-2">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">180</span><h2 class="font-semibold text-gray-800">Comissões</h2></div>
            <form method="GET" action="{{ route('financeiro.emissoes.comissoes') }}" target="_blank" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Início <span class="text-red-500">*</span></label>
                    <input type="date" name="data_inicio" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Fim <span class="text-red-500">*</span></label>
                    <input type="date" name="data_fim" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Percentual (%) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0" max="100" name="percentual" value="5" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700"><i class="fa-solid fa-file-pdf mr-1"></i> Gerar PDF</button>
            </form>
            <p class="text-xs text-gray-500 mt-2">Comissão calculada como percentual sobre os recebimentos pagos no período.</p>
        </div>
    </div>
</div>
@endsection
