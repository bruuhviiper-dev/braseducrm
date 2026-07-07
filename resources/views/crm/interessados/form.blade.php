@extends('layouts.app')
@section('title', 'Cadastro de Interessados (CRM)')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-5 py-3 border-b flex items-center gap-3">
            <a href="{{ route('crm.interessados.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <span class="text-sm font-semibold text-gray-400">108</span>
            <div>
                <h1 class="text-lg font-bold text-gray-800">Cadastro de Interessados <span class="text-gray-400 font-normal">(CRM)</span></h1>
                <p class="text-xs text-primary-500">CRM › Interessados</p>
            </div>
        </div>
        <div class="px-5 pt-3 border-b">
            <span class="inline-block pb-2 text-sm font-semibold text-cyan-600 border-b-2 border-cyan-500">Dados básicos</span>
        </div>
        <form method="POST" action="{{ ($interessado ?? null) ? route('crm.interessados.update', $interessado) : route('crm.interessados.store') }}" class="p-5 space-y-4">
            @csrf
            @if($interessado ?? null) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $interessado->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                <input type="email" name="email" value="{{ old('email', $interessado->email ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Código do País</label>
                    <select name="codigo_pais" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        @foreach(['+55' => '+55 (Brasil)', '+351' => '+351 (Portugal)', '+1' => '+1 (EUA)', '+595' => '+595 (Paraguai)', '+598' => '+598 (Uruguai)'] as $cod => $lbl)
                        <option value="{{ $cod }}" @selected(old('codigo_pais', $interessado->codigo_pais ?? '+55') == $cod)>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Celular</label>
                    <input type="text" name="celular" value="{{ old('celular', $interessado->celular ?? '') }}" placeholder="(00) 00000-0000" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">CPF</label>
                <input type="text" name="cpf" value="{{ old('cpf', $interessado->cpf ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">De qual empresa?</label>
                <input type="text" name="e_empresa" value="{{ old('e_empresa', $interessado->e_empresa ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Responsável (Somente esse consultor poderá gerar novas oportunidades para esse interessado)</label>
                <select name="responsavel_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Selecione...</option>
                    @foreach($consultores as $c)
                    <option value="{{ $c->id }}" @selected(old('responsavel_id', $interessado->responsavel_id ?? '') == $c->id)>{{ $c->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pessoa (Opcional)</label>
                <select name="pessoa_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Selecione...</option>
                    @foreach($pessoas as $p)
                    <option value="{{ $p->id }}" @selected(old('pessoa_id', $interessado->pessoa_id ?? '') == $p->id)>{{ $p->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                <select name="categoria_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Selecione...</option>
                    @foreach($categorias as $cat)
                    <option value="{{ $cat->id }}" @selected(old('categoria_id', $interessado->categoria_id ?? '') == $cat->id)>{{ $cat->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cidade (Opcional)</label>
                <input type="text" name="cidade" value="{{ old('cidade', $interessado->cidade ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Profissão (Opcional)</label>
                <select name="profissao_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Selecione...</option>
                    @foreach($profissoes as $pr)
                    <option value="{{ $pr->id }}" @selected(old('profissao_id', $interessado->profissao_id ?? '') == $pr->id)>{{ $pr->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Formação</label>
                <input type="text" name="formacao" value="{{ old('formacao', $interessado->formacao ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Instagram (Somente nome de usuario EX: eduqtecnologia)</label>
                <input type="text" name="instagram" value="{{ old('instagram', $interessado->instagram ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Facebook (Somente nome de usuario EX: eduqtecnologia)</label>
                <input type="text" name="facebook" value="{{ old('facebook', $interessado->facebook ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Produtos e serviços</label>
                <select name="curso_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Selecione...</option>
                    @foreach($cursos as $cur)
                    <option value="{{ $cur->id }}" @selected(old('curso_id', $interessado->curso_id ?? '') == $cur->id)>{{ $cur->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Informações Adicionais</label>
                <textarea name="observacoes" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">{{ old('observacoes', $interessado->observacoes ?? '') }}</textarea>
            </div>

            <input type="hidden" name="ativo" value="1">

            <div class="flex justify-end pt-3 sticky bottom-4 z-10">
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30"><i class="fa-solid fa-check mr-1"></i>Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
