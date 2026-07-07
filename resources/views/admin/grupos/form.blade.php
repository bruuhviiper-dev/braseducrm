@extends('layouts.app')
@section('title', 'Cadastro de Grupo de Operadores')

@section('content')
<div class="w-full" x-data="grupoOperadoresForm()">
    <div class="bg-white">
        <div class="px-5 py-3 border-b flex items-center gap-2">
            <span class="text-sm font-semibold text-gray-400">43</span>
            <h1 class="text-lg font-bold text-gray-800">Cadastro de Grupo de Operadores</h1>
        </div>
        <div class="px-5 pt-3 border-b flex gap-5">
            <button type="button" @click="aba = 'permissoes'" :class="aba === 'permissoes' ? 'text-cyan-600 border-cyan-500' : 'text-gray-500 border-transparent'" class="pb-2 text-sm font-semibold border-b-2">Permissões</button>
            <button type="button" @click="aba = 'horarios'" :class="aba === 'horarios' ? 'text-cyan-600 border-cyan-500' : 'text-gray-500 border-transparent'" class="pb-2 text-sm font-semibold border-b-2">Horários liberados</button>
        </div>
        <form method="POST" action="{{ isset($grupo) ? route('admin.grupos.update', $grupo) : route('admin.grupos.store') }}" class="p-5 space-y-4">
            @csrf
            @if(isset($grupo)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div x-show="aba === 'permissoes'" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Copiar permissões de outro grupo de operadores</label>
                    <select @change="copiarDe($event.target.value)" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <option value="">Selecione...</option>
                        <template x-for="(g, id) in grupos" :key="id">
                            <option :value="id" x-text="g.nome"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $grupo->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
                </div>
                <input type="hidden" name="descricao" value="{{ old('descricao', $grupo->descricao ?? '') }}">
                <input type="hidden" name="ativo" value="1">

                <div class="border-t pt-4">
                    <h3 class="text-sm font-bold text-gray-700 mb-3">Funções do sistema</h3>
                    @php($sel = $selecionadas ?? [])
                    <div class="space-y-2">
                        @forelse($funcoesPorModulo as $modulo => $funcoes)
                        <div class="border rounded-xl overflow-hidden" x-data="{ open: false }">
                            <div class="bg-gray-50 px-4 py-2.5 flex items-center justify-between cursor-pointer" @click="open = !open">
                                <span class="text-sm font-semibold text-gray-700 capitalize">{{ $modulo }}</span>
                                <div class="flex items-center gap-3 text-xs">
                                    <button type="button" @click.stop="marcarTodos('{{ $modulo }}', true)" class="text-cyan-600 hover:underline">Todos</button>
                                    <button type="button" @click.stop="marcarTodos('{{ $modulo }}', false)" class="text-gray-400 hover:underline">Nenhum</button>
                                    <i class="fa-solid fa-chevron-down text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                </div>
                            </div>
                            <div x-show="open" x-cloak class="p-3 grid grid-cols-1 sm:grid-cols-2 gap-1.5">
                                @foreach($funcoes as $f)
                                <label class="flex items-center gap-2 text-sm text-gray-600 hover:bg-gray-50 rounded px-2 py-1 cursor-pointer">
                                    <input type="checkbox" name="funcoes[]" value="{{ $f->id }}" data-modulo="{{ $modulo }}" {{ in_array($f->id, old('funcoes', $sel)) ? 'checked' : '' }} class="rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                                    <span><span class="text-gray-400 mr-1">{{ $f->codigo }}</span>{{ $f->nome }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-gray-400">Nenhuma função cadastrada (tabela funcoes vazia).</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div x-show="aba === 'horarios'" x-cloak>
                <p class="text-sm text-gray-500">Sem restrição de horários — os operadores deste grupo podem acessar o sistema a qualquer momento.</p>
            </div>

            <div class="flex justify-end pt-3 sticky bottom-4 z-10">
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30"><i class="fa-solid fa-check mr-1"></i>Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
function grupoOperadoresForm() {
    return {
        aba: 'permissoes',
        grupos: @json($gruposCopia ?? []),
        copiarDe(id) {
            if (!id || !this.grupos[id]) return;
            const ids = this.grupos[id].funcoes.map(String);
            document.querySelectorAll('input[name="funcoes[]"]').forEach(c => c.checked = ids.includes(c.value));
        },
        marcarTodos(modulo, val) {
            document.querySelectorAll('[data-modulo="' + modulo + '"]').forEach(c => c.checked = val);
        },
    };
}
</script>
@endsection
