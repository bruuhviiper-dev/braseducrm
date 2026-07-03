@extends('layouts.app')
@section('title', 'Cadastro de Turma')

@section('content')
<div class="max-w-3xl mx-auto"
     x-data="{
        aba: 'dados',
        finalizada: {{ old('finalizada', $turma->finalizada ?? false) ? 'true' : 'false' }},
        comissionavel: {{ old('comissionavel', $turma->comissionavel ?? false) ? 'true' : 'false' }},
        naoEnviar: {{ old('nao_enviar_contrato', $turma->nao_enviar_contrato ?? false) ? 'true' : 'false' }}
     }">
    <div class="bg-white rounded-xl border">
        <div class="px-5 py-3 border-b flex items-center gap-3">
            <a href="{{ route('academico.turmas.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <span class="text-sm font-semibold text-gray-400">40</span>
            <div>
                <h1 class="text-lg font-bold text-gray-800">Cadastro de Turma</h1>
                <p class="text-xs text-primary-500">Acadêmico › Turmas</p>
            </div>
        </div>
        <div class="px-5 pt-3 border-b flex gap-5">
            @foreach(['dados' => 'Dados Básicos', 'comissoes' => 'Comissões', 'config' => 'Configuração'] as $k => $lbl)
            <button type="button" @click="aba = '{{ $k }}'" :class="aba === '{{ $k }}' ? 'text-cyan-600 border-cyan-500' : 'text-gray-500 border-transparent'" class="pb-2 text-sm font-semibold border-b-2">{{ $lbl }}</button>
            @endforeach
        </div>
        <form method="POST" action="{{ ($turma ?? null) ? route('academico.turmas.update', $turma) : route('academico.turmas.store') }}" class="p-5 space-y-4">
            @csrf
            @if($turma ?? null) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div x-show="aba === 'dados'" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SIGLA</label>
                    <input type="text" name="codigo" value="{{ old('codigo', $turma->codigo ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $turma->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Instituição de Ensino</label>
                    <select name="instituicao_ensino_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <option value="">Selecione...</option>
                        @foreach($instituicoes as $ie)
                        <option value="{{ $ie->id }}" @selected(old('instituicao_ensino_id', $turma->instituicao_ensino_id ?? '') == $ie->id)>{{ $ie->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Matriz Curricular <span class="text-red-500">*</span></label>
                    <select name="matriz_curricular_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
                        <option value="">Selecione...</option>
                        @foreach($matrizes as $m)
                        <option value="{{ $m->id }}" @selected(old('matriz_curricular_id', $turma->matriz_curricular_id ?? '') == $m->id)>{{ $m->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Turno</label>
                    <select name="turno_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <option value="">Selecione...</option>
                        @foreach($turnos as $t)
                        <option value="{{ $t->id }}" @selected(old('turno_id', $turma->turno_id ?? '') == $t->id)>{{ $t->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade máxima de alunos</label>
                    <input type="number" min="0" name="vagas" value="{{ old('vagas', $turma->vagas ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                </div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="finalizada" :value="finalizada ? 1 : 0">
                    <button type="button" @click="finalizada = !finalizada" :class="finalizada ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                        <span :class="finalizada ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                    </button>
                    <span class="text-sm font-medium text-gray-700">Turma finalizada?</span>
                </label>
            </div>

            <div x-show="aba === 'comissoes'" x-cloak class="space-y-4">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="comissionavel" :value="comissionavel ? 1 : 0">
                    <button type="button" @click="comissionavel = !comissionavel" :class="comissionavel ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                        <span :class="comissionavel ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                    </button>
                    <span class="text-sm font-medium text-gray-700">Turma é comissionável?</span>
                </label>
            </div>

            <div x-show="aba === 'config'" x-cloak class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cor (Exemplo: #264bac)</label>
                    <input type="text" name="cor" value="{{ old('cor', $turma->cor ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Modelo de Documento (Contrato)</label>
                    <select name="modelo_documento_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <option value="">Selecione...</option>
                        @foreach($modelos as $m)
                        <option value="{{ $m->id }}" @selected(old('modelo_documento_id', $turma->modelo_documento_id ?? '') == $m->id)>{{ $m->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Conta</label>
                    <select name="conta_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <option value="">Selecione...</option>
                        @foreach($contas as $c)
                        <option value="{{ $c->id }}" @selected(old('conta_id', $turma->conta_id ?? '') == $c->id)>{{ $c->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cidade onde as aulas são ministradas</label>
                    <input type="text" name="cidade_aulas" value="{{ old('cidade_aulas', $turma->cidade_aulas ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo da Turma</label>
                    <select name="tipo_turma" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <option value="">Selecione...</option>
                        @foreach(['Presencial', 'EAD', 'Híbrida'] as $tt)
                        <option value="{{ $tt }}" @selected(old('tipo_turma', $turma->tipo_turma ?? '') == $tt)>{{ $tt }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição simplificada do horário</label>
                    <textarea name="descricao_horario" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">{{ old('descricao_horario', $turma->descricao_horario ?? '') }}</textarea>
                </div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="nao_enviar_contrato" :value="naoEnviar ? 1 : 0">
                    <button type="button" @click="naoEnviar = !naoEnviar" :class="naoEnviar ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                        <span :class="naoEnviar ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                    </button>
                    <span class="text-sm font-medium text-gray-700">Não enviar contrato para essa turma</span>
                </label>
            </div>

            <div class="flex justify-end pt-3 border-t">
                <button type="submit" class="px-6 py-2 bg-cyan-500 hover:bg-cyan-400 text-white rounded-lg text-sm font-semibold"><i class="fa-solid fa-check mr-1"></i>Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
