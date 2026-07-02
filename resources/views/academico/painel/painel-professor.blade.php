@extends('layouts.app')
@section('title', 'Painel do Professor')

@section('content')
<div class="flex flex-col lg:flex-row gap-4">
    {{-- Área principal --}}
    <div class="flex-1 order-2 lg:order-1">
        <div class="bg-white rounded-xl border">
            <div class="px-5 py-3 border-b flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">257</span>
                    <h1 class="text-lg font-semibold text-gray-800">Painel do Professor</h1>
                </div>
                {{-- Alternador de visão (fiel ao EDUQ: Notas / Frequências / EAD) --}}
                <div class="flex items-center gap-1">
                    @php $qs = $request->only(['turma_montada_id','professor_id','inicio','fim']); @endphp
                    <a href="{{ route('academico.painel-professor.index', array_merge($qs, ['view'=>'notas'])) }}"
                       class="px-3 py-1.5 rounded-lg text-sm font-medium border {{ $view==='notas' ? 'bg-primary-600 text-white border-primary-600' : 'text-gray-600 border-gray-200 hover:bg-gray-50' }}"><i class="fa-solid fa-list-ol mr-1"></i>Notas</a>
                    <a href="{{ route('academico.painel-professor.index', array_merge($qs, ['view'=>'frequencias'])) }}"
                       class="px-3 py-1.5 rounded-lg text-sm font-medium border {{ $view==='frequencias' ? 'bg-primary-600 text-white border-primary-600' : 'text-gray-600 border-gray-200 hover:bg-gray-50' }}"><i class="fa-solid fa-square-check mr-1"></i>Frequências</a>
                    <span class="px-3 py-1.5 rounded-lg text-sm font-medium border text-gray-300 border-gray-100 cursor-not-allowed" title="Indisponível"><i class="fa-solid fa-video mr-1"></i>EAD</span>
                </div>
            </div>

            @if(!$consultou)
            <div class="text-center py-16 text-gray-400">
                <i class="fa-solid fa-box-open text-3xl mb-2"></i>
                <p class="text-sm">Selecione os filtros ao lado e clique em Consultar.</p>
            </div>
            @elseif($linhas->isEmpty())
            <div class="text-center py-16 text-gray-400">
                <i class="fa-solid fa-box-open text-3xl mb-2"></i>
                <p class="text-sm">Nada encontrado. Nenhum item encontrado.</p>
            </div>
            @else
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Aluno</th>
                        @if($view==='notas')
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Média</th>
                        @else
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Presenças</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Faltas</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Frequência</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($linhas as $l)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $l['aluno'] }}</td>
                        @if($view==='notas')
                        <td class="px-4 py-3 text-center font-semibold {{ $l['media'] !== null ? 'text-gray-700' : 'text-gray-400' }}">{{ $l['media'] !== null ? number_format($l['media'], 2, ',', '.') : '—' }}</td>
                        @else
                        <td class="px-4 py-3 text-center text-green-600">{{ $l['presencas'] }}</td>
                        <td class="px-4 py-3 text-center text-red-600">{{ $l['faltas'] }}</td>
                        <td class="px-4 py-3 text-center text-gray-600">{{ $l['frequencia'] !== null ? number_format($l['frequencia'],1,',','.').'%' : '—' }}</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

    {{-- Painel de filtros (direita, fiel ao EDUQ) --}}
    <div class="w-full lg:w-72 shrink-0 order-1 lg:order-2">
        <form method="GET" action="{{ route('academico.painel-professor.index') }}" class="bg-white rounded-xl border p-4 space-y-3"
              x-data="{
                aplicaPeriodo(v) {
                    if(!v) return;
                    const hoje = new Date();
                    this.$refs.fim.value = hoje.toISOString().slice(0,10);
                    this.$refs.inicio.value = new Date(hoje.getTime() - v*86400000).toISOString().slice(0,10);
                }
              }">
            <input type="hidden" name="view" value="{{ $view }}">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Período</label>
                <select @change="aplicaPeriodo($event.target.value)" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Personalizado</option>
                    <option value="7">Essa semana</option>
                    <option value="30">Últimos 30 dias</option>
                    <option value="90">Últimos 90 dias</option>
                    <option value="365">Últimos 365 dias</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Início <span class="text-red-500">*</span></label>
                    <input type="date" name="inicio" x-ref="inicio" value="{{ $request->inicio }}" class="w-full border rounded-lg px-2 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Fim <span class="text-red-500">*</span></label>
                    <input type="date" name="fim" x-ref="fim" value="{{ $request->fim }}" class="w-full border rounded-lg px-2 py-2 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Turma Montada</label>
                <select name="turma_montada_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Selecione...</option>
                    @foreach($turmasMontadas as $t)<option value="{{ $t->id }}" {{ (string)$request->turma_montada_id === (string)$t->id ? 'selected' : '' }}>{{ $t->sigla ?? $t->nome ?? $t->turma?->nome ?? ('TM #'.$t->id) }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Professores</label>
                <select name="professor_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Todos...</option>
                    @foreach($professores as $p)<option value="{{ $p->id }}" {{ (string)$request->professor_id === (string)$p->id ? 'selected' : '' }}>{{ $p->pessoa?->nome ?? 'Profissional '.$p->id }}</option>@endforeach
                </select>
            </div>
            <button type="submit" class="w-full px-4 py-2.5 bg-primary-600 text-white rounded-lg text-sm font-semibold hover:bg-primary-700"><i class="fa-solid fa-magnifying-glass mr-1"></i> Consultar</button>
        </form>
    </div>
</div>
@endsection
