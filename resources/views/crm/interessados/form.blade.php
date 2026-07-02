@extends('layouts.app')
@section('title', ($interessado ?? null) ? 'Editar Interessado' : 'Cadastro de Interessados')

@section('content')
@php
    $contIni = ($interessado ?? null) ? $interessado->contatos->map(fn($c)=>['nome'=>$c->nome,'telefone'=>$c->telefone,'email'=>$c->email])->values() : [];
@endphp
<div class="max-w-4xl mx-auto" x-data="interessadoForm(@js($contIni))">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <a href="{{ route('crm.interessados.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">108</span>
            <div>
                <h1 class="text-lg font-semibold text-gray-800">Cadastro de Interessados <span class="text-gray-400 font-normal">(CRM)</span></h1>
                <p class="text-xs text-gray-400">CRM › Interessados</p>
            </div>
        </div>

        {{-- Aba única "Dados básicos" (fiel ao EDUQ) --}}
        <div class="border-b px-4">
            <span class="inline-block px-4 py-2.5 text-sm font-medium border-b-2 border-primary-600 text-primary-600">Dados básicos</span>
        </div>

        <form method="POST" action="{{ ($interessado ?? null) ? route('crm.interessados.update', $interessado) : route('crm.interessados.store') }}" class="p-6">
            @csrf
            @if($interessado ?? null) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-4">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="flex flex-wrap items-center gap-6 mb-4">
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="e_empresa" value="1" {{ old('e_empresa', $interessado->e_empresa ?? false) ? 'checked' : '' }} class="rounded text-primary-600"> É uma empresa</label>
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="nao_enviar_mensagens" value="1" {{ old('nao_enviar_mensagens', $interessado->nao_enviar_mensagens ?? false) ? 'checked' : '' }} class="rounded text-primary-600"> Não enviar mais mensagens?</label>
                @if($interessado ?? null)
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="ativo" value="1" {{ old('ativo', $interessado->ativo ?? true) ? 'checked' : '' }} class="rounded text-primary-600"> Ativo</label>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $interessado->nome ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">CPF</label>
                    <input type="text" name="cpf" value="{{ old('cpf', $interessado->cpf ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                    <input type="email" name="email" value="{{ old('email', $interessado->email ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Celular</label>
                    <input type="text" name="celular" value="{{ old('celular', $interessado->celular ?? '') }}" placeholder="+55 (00) 00000-0000" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                    <input type="text" name="telefone" value="{{ old('telefone', $interessado->telefone ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Origem</label>
                    <select name="origem_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($origens as $o)<option value="{{ $o->id }}" @selected(old('origem_id', $interessado->origem_id ?? '')==$o->id)>{{ $o->nome }}</option>@endforeach
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Responsável <span class="text-gray-400 text-xs">(só este consultor gera oportunidades)</span></label>
                    <select name="responsavel_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($consultores as $c)<option value="{{ $c->id }}" @selected(old('responsavel_id', $interessado->responsavel_id ?? '')==$c->id)>{{ $c->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pessoa (Opcional)</label>
                    <select name="pessoa_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($pessoas as $p)<option value="{{ $p->id }}" @selected(old('pessoa_id', $interessado->pessoa_id ?? '')==$p->id)>{{ $p->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                    <select name="categoria_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($categorias as $cat)<option value="{{ $cat->id }}" @selected(old('categoria_id', $interessado->categoria_id ?? '')==$cat->id)>{{ $cat->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cidade (Opcional)</label>
                    <input type="text" name="cidade" value="{{ old('cidade', $interessado->cidade ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Profissão (Opcional)</label>
                    <select name="profissao_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($profissoes as $pr)<option value="{{ $pr->id }}" @selected(old('profissao_id', $interessado->profissao_id ?? '')==$pr->id)>{{ $pr->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Formação</label>
                    <input type="text" name="formacao" value="{{ old('formacao', $interessado->formacao ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Instagram</label>
                    <input type="text" name="instagram" value="{{ old('instagram', $interessado->instagram ?? '') }}" placeholder="usuario" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Facebook</label>
                    <input type="text" name="facebook" value="{{ old('facebook', $interessado->facebook ?? '') }}" placeholder="usuario" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Produtos e serviços (Curso)</label>
                    <select name="curso_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($cursos as $cur)<option value="{{ $cur->id }}" @selected(old('curso_id', $interessado->curso_id ?? '')==$cur->id)>{{ $cur->nome }}</option>@endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Informações Adicionais</label>
                <textarea name="observacoes" rows="3" maxlength="2000" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('observacoes', $interessado->observacoes ?? '') }}</textarea>
            </div>

            {{-- Outros Contatos (repeater, fiel ao EDUQ) --}}
            <div class="mt-5 border-t pt-4">
                <div class="flex justify-between items-center mb-2">
                    <p class="text-sm font-medium text-gray-700">Outros Contatos</p>
                    <button type="button" @click="addContato()" class="px-3 py-1.5 bg-primary-600 text-white rounded-lg text-xs font-medium hover:bg-primary-700"><i class="fa-solid fa-plus mr-1"></i> Contato</button>
                </div>
                <template x-for="(c,i) in contatos" :key="i">
                    <div class="border rounded-lg p-3 bg-gray-50 grid grid-cols-1 md:grid-cols-6 gap-2 items-center mb-2">
                        <input type="text" :name="`contatos[${i}][nome]`" x-model="c.nome" placeholder="Nome" class="border rounded px-2 py-1.5 text-sm md:col-span-2">
                        <input type="text" :name="`contatos[${i}][telefone]`" x-model="c.telefone" placeholder="Telefone" class="border rounded px-2 py-1.5 text-sm md:col-span-2">
                        <input type="text" :name="`contatos[${i}][email]`" x-model="c.email" placeholder="E-mail" class="border rounded px-2 py-1.5 text-sm">
                        <button type="button" @click="contatos.splice(i,1)" class="p-2 text-red-600 hover:bg-red-50 rounded justify-self-end"><i class="fa-solid fa-trash text-xs"></i></button>
                    </div>
                </template>
                <p x-show="contatos.length===0" class="text-xs text-gray-400">Nenhum contato adicional.</p>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t">
                <a href="{{ route('crm.interessados.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
function interessadoForm(contIni) {
    return {
        contatos: (contIni||[]).map(c=>({nome:c.nome??'',telefone:c.telefone??'',email:c.email??''})),
        addContato() { this.contatos.push({nome:'',telefone:'',email:''}); },
    };
}
</script>
@endsection
