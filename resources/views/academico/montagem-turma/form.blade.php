@extends('layouts.app')
@section('title', isset($turmaMontada) ? 'Editar Montagem de Turma' : 'Montagem de Turma e Horário')

@section('content')
@php
    $cont = isset($turmaMontada) ? $turmaMontada->contadores() : ['matriculados'=>0,'nao_confirmados'=>0,'concluidos'=>0,'cancelados'=>0,'total'=>0];
@endphp
<div class="max-w-5xl mx-auto" x-data="montagemForm({{ isset($turmaMontada) ? $turmaMontada->horarios->map(fn($h) => ['disciplina_id' => $h->disciplina_id, 'profissional_id' => $h->profissional_id, 'sala_id' => $h->sala_id, 'dia_semana' => $h->dia_semana, 'hora_inicio' => substr($h->hora_inicio,0,5), 'hora_fim' => substr($h->hora_fim,0,5)])->values()->toJson() : '[]' }})">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <a href="{{ route('academico.montagem-turma.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">41</span>
            <div>
                <h1 class="text-lg font-semibold text-gray-800">Montagem de Turma e Horário</h1>
                <p class="text-xs text-gray-400">Acadêmico › Turmas</p>
            </div>
        </div>

        {{-- Abas (estilo EDUQ) --}}
        <div class="border-b px-4 flex gap-1 overflow-x-auto">
            @foreach(['dados'=>'Dados Acadêmicos','disciplinas'=>'Disciplinas e Matrículas','cronograma'=>'Cronograma'] as $k => $t)
            <button type="button" @click="tab='{{ $k }}'" :class="tab==='{{ $k }}' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-2.5 text-sm font-medium border-b-2 whitespace-nowrap">{{ $t }}</button>
            @endforeach
        </div>

        <form method="POST" action="{{ isset($turmaMontada) ? route('academico.montagem-turma.update', $turmaMontada) : route('academico.montagem-turma.store') }}" class="p-6">
            @csrf
            @if(isset($turmaMontada)) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-4">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- ============ DADOS ACADÊMICOS ============ --}}
            <div x-show="tab==='dados'" class="space-y-5">
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="ativo" value="1" {{ old('ativo', $turmaMontada->ativo ?? true) ? 'checked' : '' }} class="rounded text-primary-600"> Ativo</label>

                {{-- Contadores (estilo EDUQ) --}}
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    @foreach([
                        ['Matriculados', $cont['matriculados'], 'text-primary-600', 'border-primary-400'],
                        ['Não Confirmados', $cont['nao_confirmados'], 'text-amber-600', 'border-amber-400'],
                        ['Concluídos', $cont['concluidos'], 'text-green-600', 'border-green-400'],
                        ['Cancelados', $cont['cancelados'], 'text-red-600', 'border-red-400'],
                        ['Total', $cont['total'], 'text-gray-700', 'border-gray-300'],
                    ] as [$lbl,$val,$txt,$bd])
                    <div class="border-t-4 {{ $bd }} border rounded-lg px-3 py-3 bg-gray-50">
                        <p class="text-[11px] uppercase tracking-wide text-gray-400 flex items-center gap-1"><i class="fa-solid fa-user-graduate"></i> {{ $lbl }}</p>
                        <p class="text-2xl font-bold {{ $txt }} mt-1">{{ $val }}</p>
                    </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SIGLA</label>
                        <input type="text" name="sigla" value="{{ old('sigla', $turmaMontada->sigla ?? '') }}" placeholder="Ex.: PED.21.2" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Situação <span class="text-red-500">*</span></label>
                        <select name="situacao" required class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="aberta" @selected(old('situacao', $turmaMontada->situacao ?? 'aberta')=='aberta')>Aberta</option>
                            <option value="em_andamento" @selected(old('situacao', $turmaMontada->situacao ?? '')=='em_andamento')>Em andamento</option>
                            <option value="finalizada" @selected(old('situacao', $turmaMontada->situacao ?? '')=='finalizada')>Finalizada</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                        <input type="text" name="nome" value="{{ old('nome', $turmaMontada->nome ?? '') }}" placeholder="Ex.: Pedagogia 2021-2" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Turma <span class="text-red-500">*</span></label>
                        <select name="turma_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach($turmas as $t)<option value="{{ $t->id }}" @selected(old('turma_id', $turmaMontada->turma_id ?? '')==$t->id)>{{ $t->nome }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Período Letivo</label>
                        <select name="periodo_letivo_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">—</option>
                            @foreach($periodos as $p)<option value="{{ $p->id }}" @selected(old('periodo_letivo_id', $turmaMontada->periodo_letivo_id ?? '')==$p->id)>{{ $p->nome }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Módulo</label>
                        <select name="modulo_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">—</option>
                            @foreach($modulos as $m)<option value="{{ $m->id }}" @selected(old('modulo_id', $turmaMontada->modulo_id ?? '')==$m->id)>{{ $m->nome }}</option>@endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- ============ DISCIPLINAS E MATRÍCULAS ============ --}}
            <div x-show="tab==='disciplinas'" x-cloak class="space-y-6">
                {{-- Grade de horários --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-700">Grade de Horários / Disciplinas</h3>
                        <button type="button" @click="add()" class="px-3 py-1.5 bg-primary-600 text-white rounded-lg text-xs font-medium hover:bg-primary-700"><i class="fa-solid fa-plus mr-1"></i>Adicionar aula</button>
                    </div>
                    <div class="space-y-2">
                        <template x-for="(h, i) in horarios" :key="i">
                            <div class="grid grid-cols-12 gap-2 items-center">
                                <select :name="`horarios[${i}][disciplina_id]`" x-model="h.disciplina_id" class="col-span-3 border rounded-lg px-2 py-1.5 text-sm">
                                    <option value="">Disciplina...</option>
                                    @foreach($disciplinas as $d)<option value="{{ $d->id }}">{{ $d->nome }}</option>@endforeach
                                </select>
                                <select :name="`horarios[${i}][profissional_id]`" x-model="h.profissional_id" class="col-span-3 border rounded-lg px-2 py-1.5 text-sm">
                                    <option value="">Professor...</option>
                                    @foreach($profissionais as $pr)<option value="{{ $pr->id }}">{{ $pr->pessoa?->nome }}</option>@endforeach
                                </select>
                                <select :name="`horarios[${i}][sala_id]`" x-model="h.sala_id" class="col-span-2 border rounded-lg px-2 py-1.5 text-sm">
                                    <option value="">Sala...</option>
                                    @foreach($salas as $s)<option value="{{ $s->id }}">{{ $s->nome }}</option>@endforeach
                                </select>
                                <select :name="`horarios[${i}][dia_semana]`" x-model="h.dia_semana" class="col-span-2 border rounded-lg px-2 py-1.5 text-sm">
                                    @foreach($diasSemana as $num => $dia)<option value="{{ $num }}">{{ $dia }}</option>@endforeach
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
            </div>

            {{-- ============ CRONOGRAMA ============ --}}
            <div x-show="tab==='cronograma'" x-cloak>
                @isset($turmaMontada)
                    @php $porDia = $turmaMontada->horarios->sortBy('hora_inicio')->groupBy('dia_semana'); @endphp
                    @if($turmaMontada->horarios->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($diasSemana as $num => $dia)
                        <div class="border rounded-lg p-3 {{ isset($porDia[$num]) ? 'bg-white' : 'bg-gray-50' }}">
                            <p class="text-sm font-semibold text-gray-700 border-b pb-1.5 mb-2">{{ $dia }}</p>
                            @forelse($porDia[$num] ?? [] as $h)
                            <div class="text-xs mb-2 pl-2 border-l-2 border-primary-400">
                                <p class="font-medium text-gray-800">{{ substr($h->hora_inicio,0,5) }}–{{ substr($h->hora_fim,0,5) }} · {{ $h->disciplina?->nome ?? '—' }}</p>
                                <p class="text-gray-400">{{ $h->profissional?->pessoa?->nome ?? 'Sem professor' }}{{ $h->sala ? ' · '.$h->sala->nome : '' }}</p>
                            </div>
                            @empty
                            <p class="text-xs text-gray-300">—</p>
                            @endforelse
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-gray-400 py-4 text-center">Nenhuma aula na grade. Monte a grade na aba "Disciplinas e Matrículas".</p>
                    @endif
                @else
                <p class="text-sm text-gray-400 py-4 text-center">Salve a turma montada para visualizar o cronograma.</p>
                @endisset
            </div>

            <div class="flex justify-end gap-3 pt-4 mt-4 border-t">
                <a href="{{ route('academico.montagem-turma.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> {{ isset($turmaMontada) ? 'Salvar' : 'Criar Turma Montada' }}</button>
            </div>
        </form>
    </div>

    {{-- Matrícula de alunos (apenas ao editar) --}}
    @isset($turmaMontada)
    <div class="bg-white rounded-xl border mt-6">
        <div class="px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">Alunos Matriculados ({{ $matriculados->count() }})</h2>
        </div>
        <div class="p-6 space-y-4">
            <form method="POST" action="{{ route('academico.montagem-turma.matricular', $turmaMontada) }}" class="flex gap-2">
                @csrf
                <select name="aluno_id" class="flex-1 border rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Selecione um aluno para matricular...</option>
                    @foreach($alunosDisponiveis as $a)<option value="{{ $a->id }}">{{ $a->pessoa?->nome }} {{ $a->ra ? '(RA '.$a->ra.')' : '' }}</option>@endforeach
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

<script>
function montagemForm(horariosIni) {
    return {
        tab: 'dados',
        horarios: horariosIni || [],
        add() { this.horarios.push({ disciplina_id:'', profissional_id:'', sala_id:'', dia_semana:1, hora_inicio:'19:00', hora_fim:'20:40' }); },
        remove(i) { this.horarios.splice(i,1); },
    };
}
</script>
@endsection
