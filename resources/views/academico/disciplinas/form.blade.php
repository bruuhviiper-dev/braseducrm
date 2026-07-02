@extends('layouts.app')
@section('title', ($disciplina ?? null) ? 'Editar Disciplina' : 'Cadastro de Disciplina')

@section('content')
<div class="max-w-5xl mx-auto" x-data="{ tab: 'basicos' }">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <a href="{{ route('academico.disciplinas.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">26</span>
            <div>
                <h1 class="text-lg font-semibold text-gray-800">{{ ($disciplina ?? null) ? 'Editar Disciplina' : 'Cadastro de Disciplina' }}</h1>
                <p class="text-xs text-gray-400">Acadêmico › Matriz Curricular</p>
            </div>
        </div>

        {{-- Abas (fiel ao EDUQ) --}}
        <div class="border-b px-4 flex gap-1 overflow-x-auto">
            @foreach(['basicos'=>'Dados Básicos','aula'=>'Detalhes de Aula','plano'=>'Plano de Ensino','matrizes'=>'Matrizes Curriculares'] as $k => $t)
            <button type="button" @click="tab='{{ $k }}'" :class="tab==='{{ $k }}' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-2.5 text-sm font-medium border-b-2 whitespace-nowrap">{{ $t }}</button>
            @endforeach
        </div>

        <form action="{{ ($disciplina ?? null) ? route('academico.disciplinas.update', $disciplina) : route('academico.disciplinas.store') }}" method="POST" class="p-6">
            @csrf
            @if($disciplina ?? null) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-4">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- ============ DADOS BÁSICOS (fiel: apenas SIGLA + Descrição) ============ --}}
            <div x-show="tab==='basicos'" class="space-y-4">
                <label class="flex items-center justify-between gap-2 text-sm border-2 border-primary-200 rounded-lg px-4 py-3 bg-white">
                    <span class="font-medium text-gray-700"><i class="fa-solid fa-circle-check text-primary-500 mr-1"></i> Ativo</span>
                    <input type="checkbox" name="ativo" value="1" {{ old('ativo', $disciplina->ativo ?? true) ? 'checked' : '' }} class="rounded text-primary-600 w-5 h-5">
                </label>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SIGLA <span class="text-red-500">*</span></label>
                    <input type="text" name="sigla" value="{{ old('sigla', $disciplina->sigla ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $disciplina->nome ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
            </div>

            {{-- ============ DETALHES DE AULA (read-only, fiel: professores por turma) ============ --}}
            <div x-show="tab==='aula'" x-cloak>
                <div class="bg-blue-50 border border-blue-100 text-blue-700 text-sm rounded-lg px-4 py-3 mb-4">
                    <i class="fa-solid fa-circle-info mr-1"></i> Lista dos professores que ministraram aula nessa disciplina, agrupados por turma.
                </div>
                @if(($disciplina ?? null) && $aulas->count())
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b"><tr>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Turma</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Professor</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Dia</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Horário</th>
                    </tr></thead>
                    <tbody class="divide-y">
                        @foreach($aulas as $a)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 font-medium text-gray-800">{{ $a->turmaMontada?->sigla ?? $a->turmaMontada?->nome ?? $a->turmaMontada?->turma?->nome ?? '—' }}</td>
                            <td class="px-4 py-2 text-gray-600">{{ $a->profissional?->pessoa?->nome ?? 'Sem professor' }}</td>
                            <td class="px-4 py-2 text-gray-600">{{ \App\Models\Horario::diasSemana()[$a->dia_semana] ?? '—' }}</td>
                            <td class="px-4 py-2 text-gray-600">{{ substr($a->hora_inicio,0,5) }}–{{ substr($a->hora_fim,0,5) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-10 text-gray-400">
                    <i class="fa-solid fa-box-open text-3xl mb-2"></i>
                    <p class="text-sm">Nada encontrado. Nenhum item encontrado.</p>
                </div>
                @endif
            </div>

            {{-- ============ PLANO DE ENSINO (fiel: Estrutura de Plano de Ensino) ============ --}}
            <div x-show="tab==='plano'" x-cloak class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estrutura de Plano de Ensino</label>
                    <select name="estrutura_plano_ensino_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($estruturasPlano as $ep)<option value="{{ $ep->id }}" @selected(old('estrutura_plano_ensino_id', $disciplina->estrutura_plano_ensino_id ?? '')==$ep->id)>{{ $ep->nome }}</option>@endforeach
                    </select>
                </div>
            </div>

            {{-- ============ MATRIZES CURRICULARES (read-only) ============ --}}
            <div x-show="tab==='matrizes'" x-cloak>
                @if(($disciplina ?? null) && $matrizes->count())
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b"><tr>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Sigla</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Descrição</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Curso</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                    </tr></thead>
                    <tbody class="divide-y">
                        @foreach($matrizes as $mz)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $mz->sigla ?? '—' }}</td>
                            <td class="px-4 py-2 font-medium text-gray-800">{{ $mz->nome }}</td>
                            <td class="px-4 py-2 text-gray-600">{{ $mz->curso?->nome ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $mz->pivot->obrigatoria ? 'Obrigatória' : 'Optativa' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-sm text-gray-400 py-6 text-center">Esta disciplina não está em nenhuma matriz curricular.</p>
                @endif
            </div>

            <div class="flex justify-end gap-3 pt-4 mt-4 border-t">
                <a href="{{ route('academico.disciplinas.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
