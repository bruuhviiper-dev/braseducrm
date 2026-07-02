@extends('layouts.app')
@section('title', ($matriz ?? null) ? 'Editar Matriz Curricular' : 'Cadastro de Matriz Curricular')

@section('content')
<div class="max-w-5xl mx-auto" x-data="matrizForm(@js($disciplinasSel))">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <a href="{{ route('academico.matrizes.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">30</span>
            <div>
                <h1 class="text-lg font-semibold text-gray-800">{{ ($matriz ?? null) ? 'Editar Matriz Curricular' : 'Cadastro de Matriz Curricular' }}</h1>
                <p class="text-xs text-gray-400">Acadêmico › Matriz Curricular</p>
            </div>
        </div>

        {{-- Abas (estilo EDUQ) --}}
        <div class="border-b px-4 flex gap-1 overflow-x-auto">
            @foreach(['basicos'=>'Informações Básicas','modulos'=>'Módulos','anotacoes'=>'Anotações'] as $k => $t)
            <button type="button" @click="tab='{{ $k }}'" :class="tab==='{{ $k }}' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-2.5 text-sm font-medium border-b-2 whitespace-nowrap">{{ $t }}</button>
            @endforeach
        </div>

        <form action="{{ ($matriz ?? null) ? route('academico.matrizes.update', $matriz) : route('academico.matrizes.store') }}" method="POST" class="p-6">
            @csrf
            @if($matriz ?? null) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-4">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- ============ INFORMAÇÕES BÁSICAS (fiel ao EDUQ: campos empilhados full-width) ============ --}}
            <div x-show="tab==='basicos'" class="space-y-4">
                <label class="flex items-center justify-between gap-2 text-sm border-2 border-primary-200 rounded-lg px-4 py-3 bg-white">
                    <span class="font-medium text-gray-700"><i class="fa-solid fa-circle-check text-primary-500 mr-1"></i> Ativo</span>
                    <input type="checkbox" name="ativo" value="1" {{ old('ativo', $matriz->ativo ?? true) ? 'checked' : '' }} class="rounded text-primary-600 w-5 h-5">
                </label>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Início da Vigência <span class="text-red-500">*</span></label>
                    <input type="date" name="inicio_vigencia" value="{{ old('inicio_vigencia', optional($matriz->inicio_vigencia ?? null)->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SIGLA <span class="text-red-500">*</span></label>
                    <input type="text" name="sigla" value="{{ old('sigla', $matriz->sigla ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $matriz->nome ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Configuração do boletim <span class="text-red-500">*</span></label>
                    <select name="configuracao_boletim_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($configBoletins as $cb)<option value="{{ $cb->id }}" @selected(old('configuracao_boletim_id', $matriz->configuracao_boletim_id ?? '')==$cb->id)>{{ $cb->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Curso <span class="text-red-500">*</span></label>
                    <select name="curso_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($cursos as $c)<option value="{{ $c->id }}" @selected(old('curso_id', $matriz->curso_id ?? '')==$c->id)>{{ $c->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Grande Área <span class="text-red-500">*</span></label>
                    <select name="area_conhecimento_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($areas as $a)<option value="{{ $a->id }}" @selected(old('area_conhecimento_id', $matriz->area_conhecimento_id ?? '')==$a->id)>{{ $a->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Grau <span class="text-red-500">*</span></label>
                    <select name="grau_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($graus as $g)<option value="{{ $g->id }}" @selected(old('grau_id', $matriz->grau_id ?? '')==$g->id)>{{ $g->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Habilitação <span class="text-red-500">*</span></label>
                    <select name="habilitacao_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($habilitacoes as $h)<option value="{{ $h->id }}" @selected(old('habilitacao_id', $matriz->habilitacao_id ?? '')==$h->id)>{{ $h->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tabela de Conceito de Notas</label>
                    <select name="tabela_avaliacao_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($tabelasAvaliacao as $ta)<option value="{{ $ta->id }}" @selected(old('tabela_avaliacao_id', $matriz->tabela_avaliacao_id ?? '')==$ta->id)>{{ $ta->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estrutura de Plano de Aula</label>
                    <select name="estrutura_plano_aula_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($estruturasPlano as $ep)<option value="{{ $ep->id }}" @selected(old('estrutura_plano_aula_id', $matriz->estrutura_plano_aula_id ?? '')==$ep->id)>{{ $ep->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estrutura de Plano de Ensino</label>
                    <select name="estrutura_plano_ensino_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($estruturasPlano as $ep)<option value="{{ $ep->id }}" @selected(old('estrutura_plano_ensino_id', $matriz->estrutura_plano_ensino_id ?? '')==$ep->id)>{{ $ep->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Carga Horária Descritiva</label>
                    <input type="text" name="carga_horaria_descritiva" value="{{ old('carga_horaria_descritiva', $matriz->carga_horaria_descritiva ?? '') }}" placeholder="Ex.: 3.200h" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Informações Adicionais</label>
                    <textarea name="observacoes" rows="3" maxlength="2000" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('observacoes', $matriz->observacoes ?? '') }}</textarea>
                </div>

                {{-- Configurações --}}
                <div class="border-t pt-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Configurações</p>
                    <div class="space-y-3">
                        <div class="flex flex-wrap items-center gap-4">
                            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="controla_horas_compl" value="1" {{ old('controla_horas_compl', $matriz->controla_horas_compl ?? false) ? 'checked' : '' }} class="rounded text-primary-600"> Controlar horas complementares?</label>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-500">Qtd.</span>
                                <input type="number" name="horas_compl" value="{{ old('horas_compl', $matriz->horas_compl ?? '') }}" min="0" class="w-24 border rounded px-2 py-1 text-sm">
                                <span class="text-xs text-gray-500">Mín. p/ aprovação</span>
                                <input type="number" name="horas_compl_min" value="{{ old('horas_compl_min', $matriz->horas_compl_min ?? '') }}" min="0" class="w-24 border rounded px-2 py-1 text-sm">
                            </div>
                        </div>
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="controla_extensao" value="1" {{ old('controla_extensao', $matriz->controla_extensao ?? false) ? 'checked' : '' }} class="rounded text-primary-600"> Controlar carga horária de extensão</label>
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="controla_estagio" value="1" {{ old('controla_estagio', $matriz->controla_estagio ?? false) ? 'checked' : '' }} class="rounded text-primary-600"> Controlar carga horária de estágio</label>
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="historico_parcial_portal" value="1" {{ old('historico_parcial_portal', $matriz->historico_parcial_portal ?? false) ? 'checked' : '' }} class="rounded text-primary-600"> Alunos podem emitir histórico parcial no portal?</label>
                    </div>
                </div>
            </div>

            {{-- ============ MÓDULOS ============ --}}
            <div x-show="tab==='modulos'" x-cloak class="space-y-4">
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="matricular_todas" value="1" {{ old('matricular_todas', $matriz->matricular_todas ?? false) ? 'checked' : '' }} class="rounded text-primary-600"> Matricular em todas as disciplinas imediatamente? <span class="text-gray-400">(ignorar os módulos definidos na matriz)</span></label>
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="permite_duplicadas" value="1" {{ old('permite_duplicadas', $matriz->permite_duplicadas ?? false) ? 'checked' : '' }} class="rounded text-primary-600"> Matriz permite disciplinas duplicadas? <span class="text-gray-400">(apenas em módulos diferentes)</span></label>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Percentual mínimo de frequência p/ aprovação (%)</label>
                        <input type="number" name="percentual_frequencia" value="{{ old('percentual_frequencia', $matriz->percentual_frequencia ?? 75) }}" min="0" max="100" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sistema Curricular</label>
                        <select name="sistema_curricular" class="w-full border rounded-lg px-3 py-2 text-sm">
                            @foreach(['Hora/Aula'=>'Hora/Aula','Crédito'=>'Crédito','Seriado'=>'Seriado'] as $val=>$lbl)
                            <option value="{{ $val }}" @selected(old('sistema_curricular', $matriz->sistema_curricular ?? 'Hora/Aula')===$val)>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Disciplinas por módulo --}}
                <div class="border-t pt-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-700">Disciplinas da Matriz</h3>
                        <button type="button" @click="addDisc()" class="px-3 py-1.5 bg-primary-600 text-white rounded-lg text-xs font-medium hover:bg-primary-700"><i class="fa-solid fa-plus mr-1"></i> Disciplina</button>
                    </div>
                    <div class="space-y-2">
                        <template x-for="(d,i) in discs" :key="i">
                            <div class="grid grid-cols-12 gap-2 items-center border rounded-lg p-2 bg-gray-50">
                                <select :name="`disciplinas[${i}][modulo_id]`" x-model="d.modulo_id" class="col-span-2 border rounded px-2 py-1.5 text-sm">
                                    <option value="">Módulo...</option>
                                    @foreach($modulos as $mo)<option value="{{ $mo->id }}">{{ $mo->nome }}</option>@endforeach
                                </select>
                                <select :name="`disciplinas[${i}][disciplina_id]`" x-model="d.disciplina_id" class="col-span-4 border rounded px-2 py-1.5 text-sm">
                                    <option value="">Disciplina...</option>
                                    @foreach($disciplinas as $di)<option value="{{ $di->id }}">{{ $di->nome }}</option>@endforeach
                                </select>
                                <select :name="`disciplinas[${i}][obrigatoria]`" x-model="d.obrigatoria" class="col-span-2 border rounded px-2 py-1.5 text-sm">
                                    <option value="1">Obrigatória</option>
                                    <option value="0">Optativa</option>
                                </select>
                                <input type="number" :name="`disciplinas[${i}][carga_horaria]`" x-model="d.carga_horaria" placeholder="C.H." min="0" class="col-span-1 border rounded px-2 py-1.5 text-sm">
                                <input type="number" :name="`disciplinas[${i}][creditos]`" x-model="d.creditos" placeholder="Créd." min="0" class="col-span-2 border rounded px-2 py-1.5 text-sm">
                                <button type="button" @click="discs.splice(i,1)" class="col-span-1 p-1.5 text-red-600 hover:bg-red-50 rounded justify-self-center"><i class="fa-solid fa-trash text-xs"></i></button>
                            </div>
                        </template>
                        <p x-show="discs.length===0" class="text-sm text-gray-400 text-center py-3">Nenhuma disciplina. Clique em "Disciplina".</p>
                    </div>
                </div>
            </div>

            {{-- ============ ANOTAÇÕES ============ --}}
            <div x-show="tab==='anotacoes'" x-cloak>
                <label class="block text-sm font-medium text-gray-700 mb-1">Anotações</label>
                <textarea name="anotacoes" rows="8" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('anotacoes', $matriz->anotacoes ?? '') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 mt-4 border-t">
                <a href="{{ route('academico.matrizes.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
function matrizForm(discIni) {
    return {
        tab: 'basicos',
        discs: (discIni||[]).map(d=>({modulo_id:d.modulo_id??'',disciplina_id:d.disciplina_id??'',obrigatoria:(d.obrigatoria?'1':'0'),carga_horaria:d.carga_horaria??'',creditos:d.creditos??''})),
        addDisc() { this.discs.push({modulo_id:'',disciplina_id:'',obrigatoria:'1',carga_horaria:'',creditos:''}); },
    };
}
</script>
@endsection
