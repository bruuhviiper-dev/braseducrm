@extends('layouts.app')
@section('title', ($curso ?? null) ? 'Editar Curso' : 'Cadastro de Curso')

@section('content')
<div class="max-w-5xl mx-auto" x-data="{ tab: 'basicos' }">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <a href="{{ route('academico.cursos.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">25</span>
            <div>
                <h1 class="text-lg font-semibold text-gray-800">{{ ($curso ?? null) ? 'Editar Curso' : 'Cadastro de Curso' }}</h1>
                <p class="text-xs text-gray-400">Acadêmico › Matriz Curricular</p>
            </div>
        </div>

        {{-- Abas (estilo EDUQ) --}}
        <div class="border-b px-4 flex gap-1 overflow-x-auto">
            @foreach(['basicos'=>'Dados Básicos','matriculas'=>'Matrículas'] as $k => $t)
            <button type="button" @click="tab='{{ $k }}'" :class="tab==='{{ $k }}' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-2.5 text-sm font-medium border-b-2 whitespace-nowrap">{{ $t }}</button>
            @endforeach
        </div>

        <form action="{{ ($curso ?? null) ? route('academico.cursos.update', $curso) : route('academico.cursos.store') }}" method="POST" class="p-6">
            @csrf
            @if($curso ?? null) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-4">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- ============ DADOS BÁSICOS ============ --}}
            <div x-show="tab==='basicos'" class="space-y-5">
                <label class="flex items-center gap-2 text-sm border rounded-lg px-4 py-3 bg-gray-50"><input type="checkbox" name="ativo" value="1" {{ old('ativo', $curso->ativo ?? true) ? 'checked' : '' }} class="rounded text-primary-600"> <span class="font-medium text-gray-700">Ativo</span></label>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SIGLA <span class="text-red-500">*</span></label>
                        <input type="text" name="sigla" value="{{ old('sigla', $curso->sigla ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Modelo de Documento (Contrato)</label>
                        <select name="modelo_documento_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach($modelos as $md)<option value="{{ $md->id }}" @selected(old('modelo_documento_id', $curso->modelo_documento_id ?? '')==$md->id)>{{ $md->nome }}</option>@endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                        <input type="text" name="nome" value="{{ old('nome', $curso->nome ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>

                {{-- Classificação acadêmica --}}
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Classificação Acadêmica</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Área de Conhecimento</label>
                            <select name="area_conhecimento_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                                <option value="">Selecione...</option>
                                @foreach($areas as $a)<option value="{{ $a->id }}" @selected(old('area_conhecimento_id', $curso->area_conhecimento_id ?? '')==$a->id)>{{ $a->nome }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Grau</label>
                            <select name="grau_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                                <option value="">Selecione...</option>
                                @foreach($graus as $g)<option value="{{ $g->id }}" @selected(old('grau_id', $curso->grau_id ?? '')==$g->id)>{{ $g->nome }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Habilitação</label>
                            <select name="habilitacao_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                                <option value="">Selecione...</option>
                                @foreach($habilitacoes as $h)<option value="{{ $h->id }}" @selected(old('habilitacao_id', $curso->habilitacao_id ?? '')==$h->id)>{{ $h->nome }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Instituição de Ensino</label>
                            <select name="instituicao_ensino_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                                <option value="">Selecione...</option>
                                @foreach($instituicoes as $i)<option value="{{ $i->id }}" @selected(old('instituicao_ensino_id', $curso->instituicao_ensino_id ?? '')==$i->id)>{{ $i->nome }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Carga Horária Total</label>
                            <input type="number" name="carga_horaria_total" value="{{ old('carga_horaria_total', $curso->carga_horaria_total ?? '') }}" min="0" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Duração (meses)</label>
                            <input type="number" name="duracao_meses" value="{{ old('duracao_meses', $curso->duracao_meses ?? '') }}" min="0" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                </div>

                {{-- Regras de matrícula / fiscal --}}
                <div class="space-y-2 border-t pt-4">
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="bloquear_menores" value="1" {{ old('bloquear_menores', $curso->bloquear_menores ?? false) ? 'checked' : '' }} class="rounded text-primary-600"> Bloquear matrícula de alunos menores de idade?</label>
                    <div>
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="nao_gerar_nf" value="1" {{ old('nao_gerar_nf', $curso->nao_gerar_nf ?? false) ? 'checked' : '' }} class="rounded text-primary-600"> Não gerar notas fiscais automaticamente para esse curso</label>
                        <p class="text-xs text-gray-400 ml-6 mt-0.5">Com essa opção ativa, parcelas deste curso não serão enviadas para geração de nota fiscal.</p>
                    </div>
                </div>

                {{-- Comissão --}}
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Comissão</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Valor da comissão (R$)</label>
                            <input type="number" step="0.01" min="0" name="valor_comissao" value="{{ old('valor_comissao', $curso->valor_comissao ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição / Observações</label>
                    <textarea name="descricao" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('descricao', $curso->descricao ?? '') }}</textarea>
                </div>
            </div>

            {{-- ============ MATRÍCULAS ============ --}}
            <div x-show="tab==='matriculas'" x-cloak>
                @if(($curso ?? null) && $curso->matriculas->count())
                <p class="text-sm text-gray-500 mb-3">{{ $curso->matriculas->count() }} matrícula(s) neste curso (via turmas).</p>
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b"><tr>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Matrícula</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Aluno</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Turma</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Situação</th>
                    </tr></thead>
                    <tbody class="divide-y">
                        @foreach($curso->matriculas->take(100) as $m)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $m->numero_matricula ?? $m->id }}</td>
                            <td class="px-4 py-2 font-medium text-gray-800">{{ $m->aluno?->pessoa?->nome ?? '—' }}</td>
                            <td class="px-4 py-2 text-gray-600">{{ $m->turma?->nome ?? '—' }}</td>
                            <td class="px-4 py-2"><span class="text-xs bg-gray-100 text-gray-700 px-2 py-0.5 rounded-full capitalize">{{ $m->situacao }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-sm text-gray-400 py-6 text-center">Nenhuma matrícula neste curso ainda.</p>
                @endif
            </div>

            <div class="flex justify-end gap-3 pt-4 mt-4 border-t">
                <a href="{{ route('academico.cursos.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
