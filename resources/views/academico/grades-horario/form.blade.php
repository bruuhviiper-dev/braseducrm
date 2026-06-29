@extends('layouts.app')
@section('title', $grade ? 'Editar Grade de Horário' : 'Nova Grade de Horário')

@php
    $aulasIniciais = old('aulas', $grade?->aulas->map(fn ($a) => [
        'hora_inicio' => substr($a->hora_inicio, 0, 5),
        'hora_fim' => substr($a->hora_fim, 0, 5),
        'hora_aula' => $a->hora_aula ? substr($a->hora_aula, 0, 5) : '',
    ])->values()->toArray() ?? []);
@endphp

@section('content')
<div class="max-w-3xl mx-auto" x-data="gradeHorario(@js($aulasIniciais))">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">36</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $grade ? 'Editar' : 'Nova' }} Grade de Horário</h1>
        </div>

        <form action="{{ $grade ? route('academico.grades-horario.update', $grade) : route('academico.grades-horario.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            @if($grade) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $grade->nome ?? '') }}" required
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Turno <span class="text-red-500">*</span></label>
                    <select name="turno_id" required class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">Selecione...</option>
                        @foreach($turnos as $turno)
                        <option value="{{ $turno->id }}" {{ (string) old('turno_id', $grade->turno_id ?? '') === (string) $turno->id ? 'selected' : '' }}>{{ $turno->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="ativo" value="1" {{ old('ativo', $grade->ativo ?? true) ? 'checked' : '' }}
                       class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                <span class="text-sm font-medium text-gray-700">Ativo</span>
            </label>

            {{-- Horários de Aula --}}
            <div class="border-t pt-4">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-sm font-semibold text-gray-800">Horários de Aula</h2>
                    <button type="button" @click="adicionar()" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                        <i class="fa-solid fa-plus mr-1"></i> Adicionar horário
                    </button>
                </div>

                <div class="bg-amber-50 border border-amber-200 text-amber-700 px-3 py-2 rounded text-xs mb-3">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                    <strong>Hora-aula</strong> é um campo de definição livre. O valor definido aqui tem prioridade sobre o intervalo (início–fim) no cálculo de hora-aula e aparece na emissão do Planejamento Diário de Aulas (função 45). Deixe em branco para usar o intervalo.
                </div>

                <table class="w-full text-sm">
                    <thead class="text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="text-left pb-1">Início</th>
                            <th class="text-left pb-1">Fim</th>
                            <th class="text-left pb-1">Hora-aula</th>
                            <th class="pb-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(aula, i) in aulas" :key="i">
                            <tr>
                                <td class="py-1 pr-2"><input type="time" :name="`aulas[${i}][hora_inicio]`" x-model="aula.hora_inicio" required class="w-full border rounded px-2 py-1.5 text-sm"></td>
                                <td class="py-1 pr-2"><input type="time" :name="`aulas[${i}][hora_fim]`" x-model="aula.hora_fim" required class="w-full border rounded px-2 py-1.5 text-sm"></td>
                                <td class="py-1 pr-2"><input type="time" :name="`aulas[${i}][hora_aula]`" x-model="aula.hora_aula" class="w-full border rounded px-2 py-1.5 text-sm"></td>
                                <td class="py-1 text-right">
                                    <button type="button" @click="remover(i)" class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash-can text-sm"></i></button>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="aulas.length === 0">
                            <td colspan="4" class="py-4 text-center text-gray-400">Nenhum horário adicionado.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('academico.grades-horario.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function gradeHorario(iniciais) {
        return {
            aulas: iniciais.length ? iniciais : [],
            adicionar() { this.aulas.push({ hora_inicio: '', hora_fim: '', hora_aula: '' }); },
            remover(i) { this.aulas.splice(i, 1); },
        };
    }
</script>
@endsection
