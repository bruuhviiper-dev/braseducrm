@extends('layouts.app')
@section('title', 'Painel Acadêmico')

@section('content')
<div class="space-y-4">
    <div class="flex items-center gap-3">
        <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">144</span>
        <div>
            <h1 class="text-lg font-semibold text-gray-800">Painel Acadêmico</h1>
            <p class="text-xs text-gray-400">Dashboard › Painéis</p>
        </div>
    </div>

    {{-- Aviso (fiel ao EDUQ) --}}
    <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl px-4 py-3 text-sm flex items-start gap-2">
        <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
        <span>Os campos marcados com <strong>*</strong> são valores totalizadores, independentes do período informado no filtro.</span>
    </div>

    {{-- KPIs totalizadores --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border p-5">
            <p class="text-sm text-blue-600 flex items-center gap-2 font-medium"><i class="fa-solid fa-user-graduate"></i> Total de matrículas (Geral) *</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totais['total'],0,',','.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Total de matrículas que já cursaram nessa instituição</p>
        </div>
        <div class="bg-white rounded-xl border p-5">
            <p class="text-sm text-teal-600 flex items-center gap-2 font-medium"><i class="fa-solid fa-graduation-cap"></i> Total concluídas (Geral) *</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totais['concluidas'],0,',','.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Total de matrículas concluídas</p>
        </div>
        <div class="bg-white rounded-xl border p-5">
            <p class="text-sm text-green-600 flex items-center gap-2 font-medium"><i class="fa-solid fa-circle-check"></i> Matrículas ativas *</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totais['ativas'],0,',','.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Total de matrículas ativas</p>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl border p-5">
            <p class="text-sm text-red-600 flex items-center gap-2 font-medium"><i class="fa-solid fa-ban"></i> Total canceladas (Geral) *</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totais['canceladas'],0,',','.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Total de matrículas canceladas</p>
        </div>
        <div class="bg-white rounded-xl border p-5">
            <p class="text-sm text-gray-500 flex items-center gap-2 font-medium"><i class="fa-solid fa-pause"></i> Total Pausadas (Geral) *</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totais['pausadas'],0,',','.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Total de matrículas pausadas (trancadas)</p>
        </div>
    </div>

    {{-- Filtros de período --}}
    <div class="bg-white rounded-xl border">
        <div class="px-5 py-2.5 border-b bg-gray-50 rounded-t-xl"><p class="text-sm font-medium text-gray-600">Filtros</p></div>
        <form method="GET" action="{{ route('paineis.academico') }}" class="p-5 grid grid-cols-1 md:grid-cols-4 gap-3"
              x-data="{ aplica(v){ if(!v) return; const h=new Date(); this.$refs.fim.value=h.toISOString().slice(0,10); this.$refs.ini.value=new Date(h.getTime()-v*86400000).toISOString().slice(0,10); } }">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Período</label>
                <select @change="aplica($event.target.value)" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Personalizado</option>
                    <option value="30">Esse mês (30 dias)</option>
                    <option value="90">Últimos 90 dias</option>
                    <option value="365">Últimos 365 dias</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Início <span class="text-red-500">*</span></label>
                <input type="date" name="inicio" x-ref="ini" value="{{ optional($inicio)->format('Y-m-d') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Fim <span class="text-red-500">*</span></label>
                <input type="date" name="fim" x-ref="fim" value="{{ optional($fim)->format('Y-m-d') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-magnifying-glass mr-1"></i> Consultar</button>
            </div>
        </form>
    </div>

    {{-- Indicadores do período --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @foreach([['Novas matrículas',$periodo['novas'],'text-blue-600','fa-user-plus'],['Matrículas concluídas',$periodo['concluidas'],'text-teal-600','fa-graduation-cap'],['Matrículas canceladas',$periodo['canceladas'],'text-red-600','fa-ban'],['Matrículas pausadas',$periodo['pausadas'],'text-gray-500','fa-pause']] as [$lbl,$val,$cor,$ic])
        <div class="bg-white rounded-xl border p-5">
            <p class="text-sm {{ $cor }} flex items-center gap-2 font-medium"><i class="fa-solid {{ $ic }}"></i> {{ $lbl }}</p>
            <p class="text-2xl font-bold text-gray-800 mt-2">{{ number_format($val,0,',','.') }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $lbl }} no período</p>
        </div>
        @endforeach
    </div>
</div>
@endsection
