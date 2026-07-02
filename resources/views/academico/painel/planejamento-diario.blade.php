@extends('layouts.app')
@section('title', 'Planejamento Diário de Aulas')

@section('content')
<div class="space-y-4">
    {{-- Filtro (fiel ao EDUQ) --}}
    <div class="bg-white rounded-xl border">
        <div class="px-5 py-3 border-b flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">45</span>
                <div>
                    <h1 class="text-lg font-semibold text-gray-800">Planejamento Diário de Aulas</h1>
                    <p class="text-xs text-gray-400">Acadêmico › Turmas</p>
                </div>
            </div>
            @if($consultou && $aulas->isNotEmpty())
            <a href="{{ route('academico.planejamento-diario.index', array_merge($request->only(['turma_montada_id','disciplina_id','professor_id','inicio','fim','sem_frequencia']), ['export'=>1])) }}"
               class="px-3 py-1.5 rounded-lg text-sm font-medium bg-green-600 text-white hover:bg-green-700"><i class="fa-solid fa-file-export mr-1"></i> Exportar</a>
            @endif
        </div>
        <form method="GET" action="{{ route('academico.planejamento-diario.index') }}" class="p-5 grid grid-cols-1 md:grid-cols-3 gap-4"
              x-data="{
                aplicaPeriodo(v){ if(!v) return; const h=new Date(); this.$refs.fim.value=h.toISOString().slice(0,10); this.$refs.inicio.value=new Date(h.getTime()-v*86400000).toISOString().slice(0,10); }
              }">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Período</label>
                <select @change="aplicaPeriodo($event.target.value)" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Personalizado</option>
                    <option value="7">Últimos 7 dias</option>
                    <option value="30">Últimos 30 dias</option>
                    <option value="90">Últimos 90 dias</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Início <span class="text-red-500">*</span></label>
                <input type="date" name="inicio" x-ref="inicio" value="{{ $request->inicio }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fim <span class="text-red-500">*</span></label>
                <input type="date" name="fim" x-ref="fim" value="{{ $request->fim }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Turma Montada <span class="text-red-500">*</span></label>
                <select name="turma_montada_id" class="w-full border rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Selecione...</option>
                    @foreach($turmasMontadas as $t)<option value="{{ $t->id }}" {{ (string)$request->turma_montada_id === (string)$t->id ? 'selected' : '' }}>{{ $t->sigla ?? $t->nome ?? $t->turma?->nome ?? ('TM #'.$t->id) }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Disciplina</label>
                <select name="disciplina_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Todas...</option>
                    @foreach($disciplinas as $d)<option value="{{ $d->id }}" {{ (string)$request->disciplina_id === (string)$d->id ? 'selected' : '' }}>{{ $d->nome }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Professor</label>
                <select name="professor_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Todos...</option>
                    @foreach($professores as $p)<option value="{{ $p->id }}" {{ (string)$request->professor_id === (string)$p->id ? 'selected' : '' }}>{{ $p->pessoa?->nome ?? 'Profissional '.$p->id }}</option>@endforeach
                </select>
            </div>
            <label class="flex items-center gap-3 text-sm md:col-span-2">
                <input type="checkbox" name="sem_frequencia" value="1" {{ $request->boolean('sem_frequencia') ? 'checked' : '' }} class="rounded text-primary-600 w-5 h-5">
                <span class="text-gray-700">Sem lançamento de frequência?</span>
            </label>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2.5 bg-primary-600 text-white rounded-lg text-sm font-semibold hover:bg-primary-700"><i class="fa-solid fa-magnifying-glass mr-1"></i> Consultar</button>
            </div>
        </form>
    </div>

    {{-- Resultados --}}
    <div class="bg-white rounded-xl border">
        @if(!$consultou)
        <div class="text-center py-16 text-gray-400"><i class="fa-solid fa-box-open text-3xl mb-2"></i><p class="text-sm">Defina o período e a turma montada e clique em Consultar.</p></div>
        @elseif($aulas->isEmpty())
        <div class="text-center py-16 text-gray-400"><i class="fa-solid fa-box-open text-3xl mb-2"></i><p class="text-sm">Nada encontrado. Nenhum item encontrado.</p></div>
        @else
        <div class="px-5 py-2 border-b text-xs text-gray-500">{{ $aulas->count() }} aula(s) no período</div>
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Data</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Dia</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Horário</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Disciplina</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Professor</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Sala</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Frequência</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($aulas as $a)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ \Carbon\Carbon::parse($a['data'])->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $a['dia'] }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $a['inicio'] }}–{{ $a['fim'] }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $a['disciplina'] }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $a['professor'] }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $a['sala'] }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($a['frequencia_lancada'])
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Lançada</span>
                        @else
                        <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">Pendente</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection
