@extends('layouts.app')
@section('title', isset($turmaMontada) ? 'Editar Montagem de Turma' : 'Nova Montagem de Turma')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Dados da turma montada + horários --}}
    <div class="bg-white rounded-lg shadow-sm border"
         x-data="{
            horarios: {{ isset($turmaMontada) ? $turmaMontada->horarios->map(fn($h) => ['disciplina_id' => $h->disciplina_id, 'profissional_id' => $h->profissional_id, 'sala_id' => $h->sala_id, 'dia_semana' => $h->dia_semana, 'hora_inicio' => substr($h->hora_inicio,0,5), 'hora_fim' => substr($h->hora_fim,0,5)])->values()->toJson() : '[]' }},
            add() { this.horarios.push({ disciplina_id:'', profissional_id:'', sala_id:'', dia_semana:1, hora_inicio:'19:00', hora_fim:'20:40' }); },
            remove(i) { this.horarios.splice(i,1); }
         }">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($turmaMontada) ? 'Editar Montagem de Turma' : 'Nova Montagem de Turma' }}</h2>
            <a href="{{ route('academico.montagem-turma.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($turmaMontada) ? route('academico.montagem-turma.update', $turmaMontada) : route('academico.montagem-turma.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($turmaMontada)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Turma <span class="text-red-500">*</span></label>
                    <select name="turma_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Selecione...</option>
                        @foreach($turmas as $t)
                        <option value="{{ $t->id }}" {{ old('turma_id', $turmaMontada->turma_id ?? '') == $t->id ? 'selected' : '' }}>{{ $t->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome da Montagem</label>
                    <input type="text" name="nome" value="{{ old('nome', $turmaMontada->nome ?? '') }}" placeholder="Ex.: Módulo 1 - 2026/1" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Módulo</label>
                    <select name="modulo_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">—</option>
                        @foreach($modulos as $m)
                        <option value="{{ $m->id }}" {{ old('modulo_id', $turmaMontada->modulo_id ?? '') == $m->id ? 'selected' : '' }}>{{ $m->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Período Letivo</label>
                    <select name="periodo_letivo_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">—</option>
                        @foreach($periodos as $p)
                        <option value="{{ $p->id }}" {{ old('periodo_letivo_id', $turmaMontada->periodo_letivo_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Situação <span class="text-red-500">*</span></label>
                    <select name="situacao" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="aberta" {{ old('situacao', $turmaMontada->situacao ?? 'aberta') == 'aberta' ? 'selected' : '' }}>Aberta</option>
                        <option value="em_andamento" {{ old('situacao', $turmaMontada->situacao ?? '') == 'em_andamento' ? 'selected' : '' }}>Em andamento</option>
                        <option value="finalizada" {{ old('situacao', $turmaMontada->situacao ?? '') == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                    </select>
                </div>
            </div>

            {{-- Grade de horários --}}
            <div class="border-t pt-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-700">Grade de Horários</h3>
                    <button type="button" @click="add()" class="text-sm text-primary-600 hover:underline"><i class="fa-solid fa-plus mr-1"></i>Adicionar aula</button>
                </div>
                <div class="space-y-2">
                    <template x-for="(h, i) in horarios" :key="i">
                        <div class="grid grid-cols-12 gap-2 items-center">
                            <select :name="`horarios[${i}][disciplina_id]`" x-model="h.disciplina_id" class="col-span-3 border rounded-lg px-2 py-1.5 text-sm">
                                <option value="">Disciplina...</option>
                                @foreach($disciplinas as $d)
                                <option value="{{ $d->id }}">{{ $d->nome }}</option>
                                @endforeach
                            </select>
                            <select :name="`horarios[${i}][profissional_id]`" x-model="h.profissional_id" class="col-span-3 border rounded-lg px-2 py-1.5 text-sm">
                                <option value="">Professor...</option>
                                @foreach($profissionais as $pr)
                                <option value="{{ $pr->id }}">{{ $pr->pessoa?->nome }}</option>
                                @endforeach
                            </select>
                            <select :name="`horarios[${i}][sala_id]`" x-model="h.sala_id" class="col-span-2 border rounded-lg px-2 py-1.5 text-sm">
                                <option value="">Sala...</option>
                                @foreach($salas as $s)
                                <option value="{{ $s->id }}">{{ $s->nome }}</option>
                                @endforeach
                            </select>
                            <select :name="`horarios[${i}][dia_semana]`" x-model="h.dia_semana" class="col-span-2 border rounded-lg px-2 py-1.5 text-sm">
                                @foreach($diasSemana as $num => $dia)
                                <option value="{{ $num }}">{{ $dia }}</option>
                                @endforeach
                            </select>
                            <input type="time" :name="`horarios[${i}][hora_inicio]`" x-model="h.hora_inicio" class="col-span-1 border rounded-lg px-1 py-1.5 text-xs">
                            <div class="col-span-1 flex items-center gap-1">
                                <input type="time" :name="`horarios[${i}][hora_fim]`" x-model="h.hora_fim" class="border rounded-lg px-1 py-1.5 text-xs w-full">
                                <button type="button" @click="remove(i)" class="text-red-600 hover:bg-red-50 rounded p-1"><i class="fa-solid fa-trash text-xs"></i></button>
                            </div>
                        </div>
                    </template>
                    <p x-show="horarios.length === 0" class="text-sm text-gray-400 text-center py-3">Nenhuma aula. Clique em "Adicionar aula".</p>
                </div>
            </div>

            <div class="flex gap-3 pt-2 border-t">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 mt-4">
                    {{ isset($turmaMontada) ? 'Salvar Alteracoes' : 'Criar Turma Montada' }}
                </button>
                <a href="{{ route('academico.montagem-turma.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 mt-4">Cancelar</a>
            </div>
        </form>
    </div>

    {{-- Matrícula de alunos (apenas ao editar) --}}
    @isset($turmaMontada)
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">Alunos Matriculados ({{ $matriculados->count() }})</h2>
        </div>
        <div class="p-6 space-y-4">
            <form method="POST" action="{{ route('academico.montagem-turma.matricular', $turmaMontada) }}" class="flex gap-2">
                @csrf
                <select name="aluno_id" class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Selecione um aluno para matricular...</option>
                    @foreach($alunosDisponiveis as $a)
                    <option value="{{ $a->id }}">{{ $a->pessoa?->nome }} {{ $a->ra ? '(RA '.$a->ra.')' : '' }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700"><i class="fa-solid fa-user-plus mr-1"></i> Matricular</button>
            </form>

            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Aluno</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">RA</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Situação</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($matriculados as $m)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 font-medium text-gray-800">{{ $m->aluno?->pessoa?->nome ?? '—' }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $m->aluno?->ra ?? '—' }}</td>
                        <td class="px-4 py-2"><span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full capitalize">{{ $m->situacao }}</span></td>
                        <td class="px-4 py-2 text-right">
                            <form method="POST" action="{{ route('academico.montagem-turma.desmatricular', [$turmaMontada, $m]) }}" onsubmit="return confirm('Remover aluno da turma?')">
                                @csrf @method('DELETE')
                                <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-user-minus"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400">Nenhum aluno matriculado ainda.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endisset
</div>
@endsection
