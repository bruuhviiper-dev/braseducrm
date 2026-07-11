@extends('layouts.app')
@section('title', 'Permissões')

@section('content')
@php $herdadas = $herdadas ?? collect(); @endphp
<div x-data="{ busca: '', modAberto: null }" class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-1">
        <div class="flex items-center gap-3">
            <a href="{{ $voltar }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <div>
                <h1 class="text-lg font-bold text-gray-800">{{ $titulo }}</h1>
                <p class="text-xs text-gray-400">{{ $subtitulo }}</p>
            </div>
        </div>
        <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" x-model="busca" placeholder="Buscar função..." class="pl-8 pr-3 py-2 border rounded-full text-sm w-64">
        </div>
    </div>
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-xs text-blue-800 mb-4">
        <i class="fa-solid fa-circle-info mr-1"></i>O usuário <b>Administrador</b> tem acesso total independentemente destas marcações. Cada usuário herda as permissões do seu departamento e pode receber liberações extras individuais.
    </div>

    <form method="POST" action="{{ $action }}">
        @csrf
        @foreach($catalogo as $modulo => $categorias)
        @php $mIdx = $loop->index; @endphp
        <div class="bg-white border rounded-xl mb-3">
            <button type="button" @click="modAberto = modAberto === {{ $mIdx }} ? null : {{ $mIdx }}"
                    class="w-full flex items-center justify-between px-4 py-3 text-left">
                <span class="font-bold text-gray-700 text-sm uppercase tracking-wide">Módulo: {{ $modulo }}</span>
                <i class="fa-solid text-gray-400" :class="modAberto === {{ $mIdx }} ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
            </button>
            <div x-show="modAberto === {{ $mIdx }} || busca.length > 1" x-cloak class="border-t divide-y">
                @foreach($categorias as $categoria => $funcoes)
                <div class="px-4 py-2">
                    <p class="text-[11px] font-bold text-gray-400 uppercase mb-1">{{ $categoria }}</p>
                    @foreach($funcoes as $f)
                    @php
                        $cod = $f['codigo'];
                        $doUsuario = collect($marcadas[$cod] ?? []);
                        $doDep = collect($herdadas[$cod] ?? []);
                    @endphp
                    <div class="py-2 border-b border-gray-50 last:border-0" x-show="!busca || @js(mb_strtolower($cod . ' ' . $f['nome'])).includes(busca.toLowerCase())">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <p class="text-sm text-gray-700"><span class="text-gray-400">{{ $cod }}</span> - {{ $f['nome'] }}</p>
                            @if($comOcultar)
                            <label class="flex items-center gap-1.5 text-xs text-gray-500 cursor-pointer">
                                <input type="checkbox" name="perms[]" value="{{ $cod }}|_ocultar_menu" @checked($doUsuario->contains('_ocultar_menu')) class="rounded text-red-500">
                                OCULTAR NO MENU
                            </label>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1 pl-4">
                            @foreach($f['acoes'] as $acao)
                            @php $herdada = $doDep->contains($acao); @endphp
                            <label class="flex items-center gap-1.5 text-xs cursor-pointer {{ $herdada ? 'text-gray-400' : 'text-gray-600' }}">
                                <input type="checkbox" name="perms[]" value="{{ $cod }}|{{ $acao }}" @checked($doUsuario->contains($acao)) {{ $herdada ? 'disabled checked' : '' }} class="rounded text-blue-500">
                                {{ $acao }}@if($herdada)<span class="text-[10px]">(depto)</span>@endif
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <div class="sticky bottom-4 flex justify-end">
            <button type="submit" class="px-8 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30"><i class="fa-solid fa-check mr-1"></i>Salvar</button>
        </div>
    </form>
</div>
@endsection
