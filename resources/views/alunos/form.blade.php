@extends('layouts.app')
@section('title', ($aluno ?? null) ? 'Editar Aluno' : 'Cadastro de Aluno')

@section('content')
@php
    $respIni = ($aluno ?? null) ? $aluno->responsaveis->map(fn($r)=>['nome'=>$r->nome,'parentesco'=>$r->parentesco,'cpf'=>$r->cpf,'telefone'=>$r->telefone,'email'=>$r->email])->values() : [];
    $formIni = ($aluno ?? null) ? $aluno->formacoes->map(fn($f)=>['nivel'=>$f->nivel,'instituicao'=>$f->instituicao,'curso'=>$f->curso,'ano_conclusao'=>$f->ano_conclusao])->values() : [];
@endphp
<div class="max-w-5xl mx-auto" x-data="alunoForm(@js($respIni), @js($formIni))">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <a href="{{ route('alunos.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">17</span>
            <div>
                <h1 class="text-lg font-semibold text-gray-800">{{ ($aluno ?? null) ? 'Editar Aluno' : 'Cadastro de Aluno' }}</h1>
                <p class="text-xs text-gray-400">Acadêmico › Matrícula</p>
            </div>
        </div>

        {{-- Abas (estilo EDUQ) --}}
        <div class="border-b px-4 flex gap-1 overflow-x-auto">
            @foreach(['basicos'=>'Dados básicos','responsaveis'=>'Responsáveis','formacao'=>'Formação acadêmica','saude'=>'Informações de saúde','historico'=>'Histórico de Movimentações'] as $k => $t)
            <button type="button" @click="tab='{{ $k }}'" :class="tab==='{{ $k }}' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-2.5 text-sm font-medium border-b-2 whitespace-nowrap">{{ $t }}</button>
            @endforeach
        </div>

        <form action="{{ ($aluno ?? null) ? route('alunos.update', $aluno) : route('alunos.store') }}" method="POST" class="p-6">
            @csrf
            @if($aluno ?? null) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-4">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- DADOS BÁSICOS --}}
            <div x-show="tab==='basicos'" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pessoa <span class="text-red-500">*</span></label>
                        <select name="pessoa_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach($pessoas as $p)<option value="{{ $p->id }}" @selected(old('pessoa_id', $aluno->pessoa_id ?? '')==$p->id)>{{ $p->nome }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Titularidade</label>
                        <select name="titularidade_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach($titularidades as $t)<option value="{{ $t->id }}" @selected(old('titularidade_id', $aluno->titularidade_id ?? '')==$t->id)>{{ $t->nome }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">RA (Registro Acadêmico)</label>
                        <input type="text" name="ra" value="{{ old('ra', $aluno->ra ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Forma de Ingresso</label>
                        <select name="forma_ingresso_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach($formasIngresso as $fi)<option value="{{ $fi->id }}" @selected(old('forma_ingresso_id', $aluno->forma_ingresso_id ?? '')==$fi->id)>{{ $fi->nome }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Data de Ingresso</label>
                        <input type="date" name="data_ingresso" value="{{ old('data_ingresso', optional($aluno->data_ingresso ?? null)->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div class="flex items-end">
                        <label class="flex items-center gap-2 text-sm pb-2"><input type="checkbox" name="ativo" value="1" {{ old('ativo', $aluno->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600"> Aluno ativo</label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Informações Adicionais</label>
                    <textarea name="informacoes_adicionais" rows="3" maxlength="2000" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('informacoes_adicionais', $aluno->informacoes_adicionais ?? '') }}</textarea>
                </div>
            </div>

            {{-- RESPONSÁVEIS --}}
            <div x-show="tab==='responsaveis'" x-cloak class="space-y-3">
                <div class="flex justify-between items-center">
                    <p class="text-sm text-gray-500">Pais ou responsáveis pelo aluno.</p>
                    <button type="button" @click="addResp()" class="px-3 py-1.5 bg-primary-600 text-white rounded-lg text-xs font-medium hover:bg-primary-700"><i class="fa-solid fa-plus mr-1"></i> Responsável</button>
                </div>
                <template x-for="(r,i) in resp" :key="i">
                    <div class="border rounded-lg p-3 bg-gray-50 grid grid-cols-1 md:grid-cols-5 gap-2 items-end">
                        <input type="text" :name="`responsaveis[${i}][nome]`" x-model="r.nome" placeholder="Nome" class="border rounded px-2 py-1.5 text-sm md:col-span-2">
                        <input type="text" :name="`responsaveis[${i}][parentesco]`" x-model="r.parentesco" placeholder="Parentesco" class="border rounded px-2 py-1.5 text-sm">
                        <input type="text" :name="`responsaveis[${i}][telefone]`" x-model="r.telefone" placeholder="Telefone" class="border rounded px-2 py-1.5 text-sm">
                        <div class="flex gap-1">
                            <input type="text" :name="`responsaveis[${i}][email]`" x-model="r.email" placeholder="E-mail" class="border rounded px-2 py-1.5 text-sm flex-1">
                            <button type="button" @click="resp.splice(i,1)" class="p-2 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </div>
                        <input type="hidden" :name="`responsaveis[${i}][cpf]`" x-model="r.cpf">
                    </div>
                </template>
                <p x-show="resp.length===0" class="text-xs text-gray-400 py-2">Nenhum responsável cadastrado.</p>
            </div>

            {{-- FORMAÇÃO ACADÊMICA --}}
            <div x-show="tab==='formacao'" x-cloak class="space-y-3">
                <div class="flex justify-between items-center">
                    <p class="text-sm text-gray-500">Formação acadêmica anterior do aluno.</p>
                    <button type="button" @click="addForm()" class="px-3 py-1.5 bg-primary-600 text-white rounded-lg text-xs font-medium hover:bg-primary-700"><i class="fa-solid fa-plus mr-1"></i> Formação</button>
                </div>
                <template x-for="(f,i) in form" :key="i">
                    <div class="border rounded-lg p-3 bg-gray-50 grid grid-cols-1 md:grid-cols-5 gap-2 items-end">
                        <select :name="`formacoes[${i}][nivel]`" x-model="f.nivel" class="border rounded px-2 py-1.5 text-sm">
                            <option value="">Nível...</option>
                            <option value="Fundamental">Fundamental</option>
                            <option value="Médio">Médio</option>
                            <option value="Técnico">Técnico</option>
                            <option value="Graduação">Graduação</option>
                            <option value="Pós-graduação">Pós-graduação</option>
                        </select>
                        <input type="text" :name="`formacoes[${i}][instituicao]`" x-model="f.instituicao" placeholder="Instituição" class="border rounded px-2 py-1.5 text-sm md:col-span-2">
                        <input type="text" :name="`formacoes[${i}][curso]`" x-model="f.curso" placeholder="Curso" class="border rounded px-2 py-1.5 text-sm">
                        <div class="flex gap-1">
                            <input type="number" :name="`formacoes[${i}][ano_conclusao]`" x-model="f.ano_conclusao" placeholder="Ano" min="1900" max="2100" class="border rounded px-2 py-1.5 text-sm flex-1">
                            <button type="button" @click="form.splice(i,1)" class="p-2 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                </template>
                <p x-show="form.length===0" class="text-xs text-gray-400 py-2">Nenhuma formação cadastrada.</p>
            </div>

            {{-- SAÚDE --}}
            <div x-show="tab==='saude'" x-cloak class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo sanguíneo</label>
                        <select name="tipo_sanguineo" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">—</option>
                            @foreach(\App\Models\Aluno::TIPOS_SANGUINEOS as $ts)<option value="{{ $ts }}" @selected(old('tipo_sanguineo', $aluno->tipo_sanguineo ?? '')==$ts)>{{ $ts }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alergia</label>
                        <select name="alergia_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Nenhuma</option>
                            @foreach($alergias as $a)<option value="{{ $a->id }}" @selected(old('alergia_id', $aluno->alergia_id ?? '')==$a->id)>{{ $a->nome }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Necessidade especial</label>
                        <select name="necessidade_especial_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Nenhuma</option>
                            @foreach($necessidades as $n)<option value="{{ $n->id }}" @selected(old('necessidade_especial_id', $aluno->necessidade_especial_id ?? '')==$n->id)>{{ $n->nome }}</option>@endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observações de saúde</label>
                    <textarea name="observacoes_saude" rows="3" maxlength="2000" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('observacoes_saude', $aluno->observacoes_saude ?? '') }}</textarea>
                </div>
            </div>

            {{-- HISTÓRICO DE MOVIMENTAÇÕES --}}
            <div x-show="tab==='historico'" x-cloak>
                @if(($aluno ?? null) && $aluno->matriculas->count())
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b"><tr>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Matrícula</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Turma</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Situação</th>
                    </tr></thead>
                    <tbody class="divide-y">
                        @foreach($aluno->matriculas as $m)
                        <tr><td class="px-4 py-2">{{ $m->numero_matricula ?? $m->id }}</td><td class="px-4 py-2">{{ $m->turma?->nome ?? '—' }}</td><td class="px-4 py-2">{{ ucfirst($m->situacao ?? '—') }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-sm text-gray-400 py-4 text-center">Nenhuma movimentação registrada.</p>
                @endif
            </div>

            <div class="flex justify-end gap-3 pt-4 mt-4 border-t">
                <a href="{{ route('alunos.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
function alunoForm(respIni, formIni) {
    return {
        tab: 'basicos',
        resp: (respIni||[]).map(r=>({nome:r.nome??'',parentesco:r.parentesco??'',cpf:r.cpf??'',telefone:r.telefone??'',email:r.email??''})),
        form: (formIni||[]).map(f=>({nivel:f.nivel??'',instituicao:f.instituicao??'',curso:f.curso??'',ano_conclusao:f.ano_conclusao??''})),
        addResp() { this.resp.push({nome:'',parentesco:'',cpf:'',telefone:'',email:''}); },
        addForm() { this.form.push({nivel:'',instituicao:'',curso:'',ano_conclusao:''}); },
    };
}
</script>
@endsection
