@extends('layouts.app')
@section('title', 'Cadastro de Calendário')

@php
    $meses = [1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril', 5 => 'Maio', 6 => 'Junho',
              7 => 'Julho', 8 => 'Agosto', 9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'];
    $diasSemana = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
@endphp

@section('content')
<div x-data="{ mes: 1 }">
    <form action="{{ route('academico.calendarios.update', $calendario) }}" method="POST" class="bg-white rounded-xl border">
        @csrf
        @method('PUT')

        <div class="px-6 py-4 border-b flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">35</span>
                <h1 class="text-lg font-semibold text-gray-800">Cadastro de Calendário — {{ $calendario->ano }}</h1>
            </div>
            <a href="{{ route('academico.calendarios.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>

        <div class="p-6 space-y-4">
            <button type="button" onclick="marcarFinaisDeSemana()"
                    class="px-3 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700">
                Marcar todos finais de semana como dias letivos (Exceto feriados)
            </button>

            <div class="space-y-1 text-sm">
                <p class="text-gray-600"><i class="fa-solid fa-square-check text-green-600 mr-1"></i> Os dias marcados são <strong class="text-green-700">LETIVOS</strong></p>
                <p class="text-gray-600"><i class="fa-regular fa-square text-gray-400 mr-1"></i> Os dias desmarcados são <strong class="text-amber-600">NÃO LETIVOS</strong></p>
            </div>

            {{-- Abas dos meses --}}
            <div class="flex flex-wrap gap-1 border-b pb-2">
                @foreach($meses as $num => $nome)
                <button type="button" @click="mes = {{ $num }}"
                        :class="mes === {{ $num }} ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-100'"
                        class="px-3 py-1.5 rounded-lg text-sm">{{ $nome }}</button>
                @endforeach
            </div>

            {{-- Dias por mês --}}
            @foreach($meses as $num => $nome)
            <div x-show="mes === {{ $num }}" x-cloak class="divide-y">
                @forelse($eventos[$num] ?? [] as $evento)
                <div class="flex items-center gap-4 py-2 dia-linha"
                     data-weekend="{{ $evento->data->isWeekend() ? 1 : 0 }}"
                     data-feriado="{{ $evento->descricao !== '' ? 1 : 0 }}">
                    <label class="flex items-center gap-2 cursor-pointer min-w-[200px]">
                        <input type="checkbox" name="letivos[]" value="{{ $evento->id }}" {{ $evento->dia_letivo ? 'checked' : '' }}
                               class="dia-check w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="text-sm text-gray-800">{{ $evento->data->format('d/m/Y') }} ({{ $diasSemana[$evento->data->dayOfWeek] }})</span>
                    </label>
                    <div class="flex-1">
                        <input type="text" name="observacao[{{ $evento->id }}]" value="{{ $evento->descricao }}" placeholder="Observação"
                               class="w-full border rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>
                @empty
                <p class="text-gray-400 text-sm py-4">Sem dias neste mês.</p>
                @endforelse
            </div>
            @endforeach
        </div>

        <div class="px-6 py-4 border-t flex justify-end">
            <button type="submit" class="px-5 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700">
                <i class="fa-solid fa-check mr-1"></i> Salvar
            </button>
        </div>
    </form>
</div>

<script>
    function marcarFinaisDeSemana() {
        document.querySelectorAll('.dia-linha').forEach(function (linha) {
            if (linha.dataset.weekend === '1' && linha.dataset.feriado !== '1') {
                linha.querySelector('.dia-check').checked = true;
            }
        });
    }
</script>
@endsection
