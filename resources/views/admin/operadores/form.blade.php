@extends('layouts.app')
@section('title', 'Cadastro de Operador')

@section('content')
<div class="max-w-3xl mx-auto"
     x-data="{
        aba: 'dados',
        isAdmin: {{ old('is_admin', $operador->is_admin ?? false) ? 'true' : 'false' }},
        trocaSenha: {{ old('exigir_troca_senha', $operador->exigir_troca_senha ?? false) ? 'true' : 'false' }}
     }">
    <div class="bg-white rounded-xl border">
        <div class="px-5 py-3 border-b flex items-center gap-2">
            <span class="text-sm font-semibold text-gray-400">44</span>
            <h1 class="text-lg font-bold text-gray-800">Cadastro de Operador</h1>
        </div>
        <div class="px-5 pt-3 border-b flex gap-5 overflow-x-auto">
            @foreach(['dados' => 'Dados Básicos', 'permissoes' => 'Permissões', 'visibilidades' => 'Outras visibilidades', 'smtp' => 'E-mail (SMTP)', 'horarios' => 'Horários liberados'] as $k => $lbl)
            <button type="button" @click="aba = '{{ $k }}'" :class="aba === '{{ $k }}' ? 'text-cyan-600 border-cyan-500' : 'text-gray-500 border-transparent'" class="pb-2 text-sm font-semibold border-b-2 whitespace-nowrap">{{ $lbl }}</button>
            @endforeach
        </div>
        <form method="POST" action="{{ isset($operador) ? route('admin.operadores.update', $operador) : route('admin.operadores.store') }}" class="p-5 space-y-4">
            @csrf
            @if(isset($operador)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div x-show="aba === 'dados'" class="space-y-4">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="is_admin" :value="isAdmin ? 1 : 0">
                    <button type="button" @click="isAdmin = !isAdmin" :class="isAdmin ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                        <span :class="isAdmin ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                    </button>
                    <span class="text-sm font-medium text-gray-700">É administrador?</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="exigir_troca_senha" :value="trocaSenha ? 1 : 0">
                    <button type="button" @click="trocaSenha = !trocaSenha" :class="trocaSenha ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                        <span :class="trocaSenha ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                    </button>
                    <span class="text-sm font-medium text-gray-700">Exigir alteração de senha no próximo login?</span>
                </label>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $operador->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Login <span class="text-red-500">*</span></label>
                    <input type="text" name="login" value="{{ old('login', $operador->login ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-mail (necessário para criar e receber notificações de tickets) <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $operador->email ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Senha @if(!isset($operador))<span class="text-red-500">*</span>@endif</label>
                    <input type="password" name="password" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" {{ isset($operador) ? '' : 'required' }} autocomplete="new-password">
                    @if(isset($operador))<p class="text-xs text-gray-400 mt-1">Deixe em branco para manter a senha atual.</p>@endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Profissional</label>
                    <select name="profissional_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <option value="">Selecione...</option>
                        @foreach($profissionais as $pf)
                        <option value="{{ $pf->id }}" {{ old('profissional_id', $operador->profissional_id ?? '') == $pf->id ? 'selected' : '' }}>{{ $pf->pessoa->nome ?? 'Profissional #' . $pf->id }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Grupo De Permissões</label>
                    <select name="grupo_operador_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <option value="">Selecione...</option>
                        @foreach($grupos as $g)
                        <option value="{{ $g->id }}" {{ old('grupo_operador_id', $operador->grupo_operador_id ?? '') == $g->id ? 'selected' : '' }}>{{ $g->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" name="ativo" value="1">
            </div>

            <div x-show="aba === 'permissoes'" x-cloak>
                <p class="text-sm text-gray-500">As permissões do operador são definidas pelo <strong>Grupo De Permissões</strong> selecionado na aba Dados Básicos.</p>
            </div>
            <div x-show="aba === 'visibilidades'" x-cloak>
                <p class="text-sm text-gray-500">Nenhuma visibilidade adicional configurada.</p>
            </div>
            <div x-show="aba === 'smtp'" x-cloak class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                    <select name="departamento_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <option value="">Selecione...</option>
                        @foreach($departamentos as $d)
                        <option value="{{ $d->id }}" {{ old('departamento_id', $operador->departamento_id ?? '') == $d->id ? 'selected' : '' }}>{{ $d->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <p class="text-sm text-gray-500">Configuração de servidor SMTP individual não habilitada nesta instalação.</p>
            </div>
            <div x-show="aba === 'horarios'" x-cloak>
                <p class="text-sm text-gray-500">Sem restrição de horários — o operador pode acessar o sistema a qualquer momento.</p>
            </div>

            <div class="flex justify-end pt-3 border-t">
                <button type="submit" class="px-6 py-2 bg-cyan-500 hover:bg-cyan-400 text-white rounded-lg text-sm font-semibold">Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
