@extends('layouts.app')
@section('title', 'Frequência e Conteúdo Ministrado')

@section('content')
<div class="space-y-6">
    {{-- Filtro (fiel ao EDUQ) --}}
    <div class="bg-white rounded-xl border">
        <div class="px-5 py-3 border-b flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">16</span>
                <div>
                    <h1 class="text-lg font-semibold text-gray-800">Frequência e Conteúdo Ministrado</h1>
                    <p class="text-xs text-gray-400">Acadêmico › Notas e Faltas</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($roster)
                <a href="{{ route('academico.frequencia.index', array_merge($request->only(['professor_id','turma_montada_id','disciplina_id','inicio','fim','somente_ativos']), ['export' => 1])) }}"
                   class="px-3 py-1.5 border border-green-500 text-green-600 rounded-lg text-sm font-medium hover:bg-green-50"><i class="fa-solid fa-file-export mr-1"></i> Exportar</a>
                @endif
            </div>
        </div>
        <form method="GET" action="{{ route('academico.frequencia.index') }}" class="p-5 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Professor <span class="text-red-500">*</span></label>
                <select name="professor_id" class="w-full border rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Selecione...</option>
                    @foreach($professores as $p)
                    <option value="{{ $p->id }}" {{ $request->professor_id == $p->id ? 'selected' : '' }}>{{ $p->pessoa?->nome ?? 'Profissional '.$p->id }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Turma Montada <span class="text-red-500">*</span></label>
                <select name="turma_montada_id" class="w-full border rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Selecione...</option>
                    @foreach($turmasMontadas as $tm)
                    <option value="{{ $tm->id }}" {{ $request->turma_montada_id == $tm->id ? 'selected' : '' }}>{{ $tm->sigla ?? $tm->nome ?? $tm->turma?->nome ?? 'Turma '.$tm->id }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Disciplina <span class="text-red-500">*</span></label>
                <select name="disciplina_id" class="w-full border rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Selecione...</option>
                    @foreach($disciplinas as $d)
                    <option value="{{ $d->id }}" {{ $request->disciplina_id == $d->id ? 'selected' : '' }}>{{ $d->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3" x-data="{
                aplicaPeriodo(v) {
                    if(!v) return;
                    const hoje = new Date();
                    const fim = hoje.toISOString().slice(0,10);
                    const ini = new Date(hoje.getTime() - v*86400000).toISOString().slice(0,10);
                    this.$refs.inicio.value = ini; this.$refs.fim.value = fim;
                }
            }">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Período</label>
                    <select @change="aplicaPeriodo($event.target.value)" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Personalizado</option>
                        <option value="7">Últimos 7 dias</option>
                        <option value="30">Últimos 30 dias</option>
                        <option value="90">Últimos 90 dias</option>
                        <option value="365">Últimos 365 dias</option>
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
            </div>
            <label class="flex items-center gap-3 text-sm">
                <input type="checkbox" name="somente_ativos" value="1" {{ $request->boolean('somente_ativos') ? 'checked' : '' }} class="rounded text-primary-600 w-5 h-5">
                <span class="text-gray-700">Carregar somente alunos ativos?</span>
            </label>
            <button type="submit" class="w-full px-4 py-3 bg-primary-600 text-white rounded-lg text-sm font-semibold hover:bg-primary-700"><i class="fa-solid fa-users mr-1"></i> Carregar Alunos</button>
        </form>
    </div>

    {{-- Chamada (alunos × datas de aula) --}}
    @if($roster)
        @if($roster['matriculas']->isEmpty())
        <div class="bg-white rounded-xl border p-8 text-center text-gray-400">Nenhum aluno matriculado nesta turma montada.</div>
        @else
        <form method="POST" action="{{ route('academico.frequencia.salvar') }}" class="bg-white rounded-xl border overflow-hidden">
            @csrf
            <input type="hidden" name="turma_montada_id" value="{{ $request->turma_montada_id }}">
            <input type="hidden" name="disciplina_id" value="{{ $request->disciplina_id }}">
            <input type="hidden" name="professor_id" value="{{ $request->professor_id }}">
            <input type="hidden" name="inicio" value="{{ $request->inicio }}">
            <input type="hidden" name="fim" value="{{ $request->fim }}">
            <input type="hidden" name="somente_ativos" value="{{ $request->boolean('somente_ativos') ? 1 : 0 }}">

            <div class="px-5 py-3 border-b flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-700">Chamada — {{ count($roster['datas']) }} aula(s) no período</h2>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-floppy-disk mr-1"></i> Salvar</button>
            </div>

            {{-- Conteúdo ministrado por data --}}
            <div class="p-4 border-b space-y-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Conteúdo Ministrado por aula</p>
                @foreach($roster['datas'] as $dt)
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500 w-24 shrink-0">{{ \Carbon\Carbon::parse($dt)->format('d/m/Y') }}</span>
                    <input type="text" name="conteudo[{{ $dt }}]" value="{{ $roster['conteudos'][$dt] ?? '' }}" placeholder="Conteúdo ministrado nesta aula" class="flex-1 border rounded px-2 py-1.5 text-sm">
                </div>
                @endforeach
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase sticky left-0 bg-gray-50">Aluno</th>
                            @foreach($roster['datas'] as $dt)
                            <th class="px-2 py-3 text-xs font-medium text-gray-500 uppercase text-center whitespace-nowrap">{{ \Carbon\Carbon::parse($dt)->format('d/m') }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($roster['matriculas'] as $m)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 font-medium text-gray-800 sticky left-0 bg-white whitespace-nowrap">{{ $m->aluno?->pessoa?->nome ?? 'Matrícula '.$m->id }}</td>
                            @foreach($roster['datas'] as $dt)
                            @php $st = $roster['registros']->get($m->id.'|'.$dt)?->status ?? 'presente'; @endphp
                            <td class="px-2 py-2 text-center">
                                <select name="status[{{ $m->id }}][{{ $dt }}]" class="border rounded px-1.5 py-1 text-xs
                                    {{ $st==='presente' ? 'text-green-700' : ($st==='ausente' ? 'text-red-700' : 'text-amber-700') }}">
                                    <option value="presente" {{ $st==='presente' ? 'selected' : '' }}>P</option>
                                    <option value="ausente" {{ $st==='ausente' ? 'selected' : '' }}>F</option>
                                    <option value="justificada" {{ $st==='justificada' ? 'selected' : '' }}>J</option>
                                </select>
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-2 border-t text-xs text-gray-400">P = Presente · F = Falta · J = Justificada</div>
        </form>
        @endif
    @else
    <div class="bg-white rounded-xl border p-8 text-center text-gray-400">Selecione professor, turma, disciplina e período para registrar a frequência.</div>
    @endif
</div>
@endsection
