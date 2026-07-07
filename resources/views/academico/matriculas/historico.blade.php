@extends('layouts.app')
@section('title', 'Histórico Escolar')

@section('content')
<div class="w-full" x-data="historicoForm(@js($itens->map(fn ($i) => ['disciplina_id' => $i->disciplina_id, 'modulo_id' => $i->modulo_id, 'media' => $i->media, 'status' => $i->status, 'observacao' => $i->observacao])->values()))">
    <div class="bg-white">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <a href="{{ route('academico.matriculas.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">23</span>
            <div>
                <h1 class="text-lg font-semibold text-gray-800">Histórico Escolar — {{ $matricula->aluno?->pessoa?->nome }}</h1>
                <p class="text-xs text-gray-400">Matrícula {{ $matricula->numero_matricula ?: '#' . $matricula->id }} · {{ $matricula->turma?->curso?->nome }} › Notas e Faltas › Histórico Escolar</p>
            </div>
        </div>

        <div class="p-6">
            <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4 text-sm text-cyan-800 mb-4">
                <p class="font-semibold mb-1"><i class="fa-solid fa-circle-info mr-1"></i> Migração de alunos antigos</p>
                <p>Para alunos que concluíram módulos em outra plataforma, lance aqui as disciplinas dos módulos anteriores com a média obtida
                no sistema antigo e o status <strong>Aprovado</strong> ou <strong>Dispensado</strong>. Isso consolida o histórico oficial
                sem recriar turmas e cronogramas — o módulo atual segue calculado automaticamente.</p>
            </div>

            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded text-sm mb-4">{{ session('success') }}</div>
            @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-4">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('academico.matriculas.historico.salvar', $matricula) }}">
                @csrf
                @method('PUT')

                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-700">Disciplinas do histórico</h3>
                    <button type="button" @click="add()" class="px-3 py-1.5 bg-primary-600 text-white rounded-lg text-xs font-medium hover:bg-primary-700"><i class="fa-solid fa-plus mr-1"></i> Disciplina</button>
                </div>

                <div class="space-y-2">
                    <template x-for="(l, i) in linhas" :key="i">
                        <div class="grid grid-cols-12 gap-2 items-center border rounded-lg p-2 bg-gray-50">
                            <select :name="`itens[${i}][modulo_id]`" x-model="l.modulo_id" class="col-span-2 border rounded px-2 py-1.5 text-sm">
                                <option value="">Módulo...</option>
                                @foreach($modulos as $m)<option value="{{ $m->id }}">{{ $m->nome }}</option>@endforeach
                            </select>
                            <select :name="`itens[${i}][disciplina_id]`" x-model="l.disciplina_id" class="col-span-4 border rounded px-2 py-1.5 text-sm">
                                <option value="">Disciplina...</option>
                                @foreach($disciplinas as $d)<option value="{{ $d->id }}">{{ $d->nome }}</option>@endforeach
                            </select>
                            <input type="number" step="0.01" min="0" max="10" :name="`itens[${i}][media]`" x-model="l.media" placeholder="Média" class="col-span-1 border rounded px-2 py-1.5 text-sm text-right">
                            <select :name="`itens[${i}][status]`" x-model="l.status" class="col-span-2 border rounded px-2 py-1.5 text-sm">
                                <option value="aprovado">Aprovado</option>
                                <option value="dispensado">Dispensado</option>
                                <option value="reprovado">Reprovado</option>
                                <option value="cursando">Cursando</option>
                            </select>
                            <input type="text" :name="`itens[${i}][observacao]`" x-model="l.observacao" placeholder="Observação" class="col-span-2 border rounded px-2 py-1.5 text-sm">
                            <button type="button" @click="linhas.splice(i,1)" class="col-span-1 p-1.5 text-red-600 hover:bg-red-50 rounded justify-self-center"><i class="fa-solid fa-trash text-xs"></i></button>
                        </div>
                    </template>
                    <p x-show="linhas.length === 0" class="text-sm text-gray-400 text-center py-4">Nenhuma disciplina lançada. Clique em "Disciplina" para incluir os módulos cursados no sistema antigo.</p>
                </div>

                <div class="flex justify-end pt-4 sticky bottom-4 z-10">
                    <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30"><i class="fa-solid fa-check mr-1"></i>Salvar Histórico</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function historicoForm(iniciais) {
    return {
        linhas: (iniciais || []).map(l => ({ disciplina_id: l.disciplina_id ?? '', modulo_id: l.modulo_id ?? '', media: l.media ?? '', status: l.status ?? 'aprovado', observacao: l.observacao ?? '' })),
        add() { this.linhas.push({ disciplina_id: '', modulo_id: '', media: '', status: 'aprovado', observacao: '' }); },
    };
}
</script>
@endsection
