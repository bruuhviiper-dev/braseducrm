@extends('layouts.app')
@section('title', $curso ? 'Editar Curso EAD' : 'Novo Curso EAD')

@section('content')
@php
    $modulosIniciais = $curso ? $curso->modulos->map(fn($m) => [
        'nome' => $m->nome,
        'aulas' => $m->aulas->map(fn($a) => [
            'titulo' => $a->titulo,
            'tipo' => $a->tipo,
            'video_ead_id' => $a->video_ead_id,
            'conteudo' => $a->conteudo,
        ])->values(),
    ])->values() : [];
@endphp
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">152</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $curso ? 'Editar Curso EAD' : 'Novo Curso EAD' }}</h1>
        </div>

        <form method="POST" action="{{ $curso ? route('ead.cursos.update', $curso) : route('ead.cursos.store') }}" class="p-6 space-y-6"
              x-data="cursoEadForm(@js($modulosIniciais))">
            @csrf
            @if($curso) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- Dados básicos --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $curso->nome ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Carga Horária</label>
                    <input type="number" name="carga_horaria" value="{{ old('carga_horaria', $curso->carga_horaria ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valor (R$)</label>
                    <input type="number" step="0.01" name="valor" value="{{ old('valor', $curso->valor ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tutor</label>
                    <select name="tutor_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($profissionais as $p)
                        <option value="{{ $p->id }}" @selected(old('tutor_id', $curso->tutor_id ?? '') == $p->id)>{{ $p->pessoa?->nome ?? ('Prof. #'.$p->id) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Agrupador</label>
                    <select name="agrupador_curso_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($agrupadores as $a)
                        <option value="{{ $a->id }}" @selected(old('agrupador_curso_id', $curso->agrupador_curso_id ?? '') == $a->id)>{{ $a->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Agrupador</label>
                    <select name="sub_agrupador_curso_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($subAgrupadores as $s)
                        <option value="{{ $s->id }}" @selected(old('sub_agrupador_curso_id', $curso->sub_agrupador_curso_id ?? '') == $s->id)>{{ $s->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                    <textarea name="descricao" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('descricao', $curso->descricao ?? '') }}</textarea>
                </div>
            </div>

            {{-- Tags --}}
            @if($tagsDisponiveis->count())
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                <div class="flex flex-wrap gap-3">
                    @foreach($tagsDisponiveis as $t)
                    <label class="flex items-center gap-2 text-sm border rounded-lg px-3 py-1.5">
                        <input type="checkbox" name="tags[]" value="{{ $t->id }}" @checked(in_array($t->id, old('tags', $tagsSelecionadas))) class="rounded border-gray-300 text-primary-600">
                        {{ $t->nome }}
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Módulos e Aulas --}}
            <div class="border-t pt-5">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-700">Conteúdo do curso (módulos e aulas)</h2>
                        <p class="text-xs text-gray-500">Organize o curso em módulos, cada um com suas aulas (vídeo, texto ou questionário).</p>
                    </div>
                    <button type="button" @click="addModulo()" class="px-3 py-1.5 bg-primary-600 text-white rounded-lg text-xs font-medium hover:bg-primary-700"><i class="fa-solid fa-plus mr-1"></i> Módulo</button>
                </div>

                <template x-for="(m, mi) in modulos" :key="mi">
                    <div class="border rounded-lg p-4 mb-3 bg-gray-50">
                        <div class="flex gap-2 items-center mb-3">
                            <span class="text-xs font-bold text-gray-400" x-text="'#' + (mi+1)"></span>
                            <input type="text" :name="`modulos[${mi}][nome]`" x-model="m.nome" placeholder="Nome do módulo" class="flex-1 border rounded-lg px-3 py-2 text-sm">
                            <button type="button" @click="removeModulo(mi)" class="p-2 text-red-600 hover:bg-red-100 rounded"><i class="fa-solid fa-trash"></i></button>
                        </div>

                        <div class="pl-4 border-l-2 border-primary-200 space-y-2">
                            <template x-for="(a, ai) in m.aulas" :key="ai">
                                <div class="bg-white border rounded-lg p-3">
                                    <div class="flex gap-2 items-center mb-2">
                                        <input type="text" :name="`modulos[${mi}][aulas][${ai}][titulo]`" x-model="a.titulo" placeholder="Título da aula" class="flex-1 border rounded-lg px-3 py-1.5 text-sm">
                                        <select :name="`modulos[${mi}][aulas][${ai}][tipo]`" x-model="a.tipo" class="border rounded-lg px-2 py-1.5 text-sm">
                                            <option value="video">Vídeo</option>
                                            <option value="texto">Texto</option>
                                            <option value="questionario">Questionário</option>
                                        </select>
                                        <button type="button" @click="removeAula(mi, ai)" class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                    <div x-show="a.tipo === 'video'">
                                        <select :name="`modulos[${mi}][aulas][${ai}][video_ead_id]`" x-model="a.video_ead_id" class="w-full border rounded-lg px-3 py-1.5 text-sm">
                                            <option value="">Selecione o vídeo...</option>
                                            @foreach($videos as $v)<option value="{{ $v->id }}">{{ $v->titulo }}</option>@endforeach
                                        </select>
                                    </div>
                                    <div x-show="a.tipo === 'texto'">
                                        <textarea :name="`modulos[${mi}][aulas][${ai}][conteudo]`" x-model="a.conteudo" rows="2" placeholder="Conteúdo da aula em texto..." class="w-full border rounded-lg px-3 py-1.5 text-sm"></textarea>
                                    </div>
                                    <p x-show="a.tipo === 'questionario'" class="text-xs text-gray-400">A aula usará o banco de questões avulsas / avaliação vinculada ao curso.</p>
                                </div>
                            </template>
                            <button type="button" @click="addAula(mi)" class="text-xs text-primary-600 hover:text-primary-700 font-medium"><i class="fa-solid fa-plus mr-1"></i> Adicionar aula</button>
                        </div>
                    </div>
                </template>
                <p x-show="modulos.length === 0" class="text-xs text-gray-400 py-2">Nenhum módulo adicionado ainda.</p>
            </div>

            <div class="flex items-center justify-between pt-4 border-t">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="ativo" value="1" {{ old('ativo', $curso->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600"> Ativo
                </label>
                <div class="flex gap-3">
                    <a href="{{ route('ead.cursos.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function cursoEadForm(iniciais) {
    return {
        modulos: (iniciais || []).map(m => ({
            nome: m.nome ?? '',
            aulas: (m.aulas || []).map(a => ({
                titulo: a.titulo ?? '',
                tipo: a.tipo ?? 'video',
                video_ead_id: a.video_ead_id ?? '',
                conteudo: a.conteudo ?? '',
            })),
        })),
        addModulo() { this.modulos.push({ nome: '', aulas: [] }); },
        removeModulo(i) { this.modulos.splice(i, 1); },
        addAula(mi) { this.modulos[mi].aulas.push({ titulo: '', tipo: 'video', video_ead_id: '', conteudo: '' }); },
        removeAula(mi, ai) { this.modulos[mi].aulas.splice(ai, 1); },
    };
}
</script>
@endsection
