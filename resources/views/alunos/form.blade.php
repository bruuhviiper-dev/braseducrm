@extends('layouts.app')
@section('title', isset($aluno) ? 'Editar Aluno' : 'Novo Aluno')

@section('content')
<div class="bg-white rounded-xl border">
    <div class="p-5 border-b flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">17</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ isset($aluno) ? 'Editar Aluno' : 'Novo Aluno' }}</h1>
        </div>
    </div>

    <form method="POST" action="{{ isset($aluno) ? route('alunos.update', $aluno) : route('alunos.store') }}">
        @csrf
        @if(isset($aluno))
            @method('PUT')
        @endif

        <div class="p-5">
            {{-- Validation Errors --}}
            @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                <ul class="text-sm text-red-600 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {{-- Pessoa --}}
                <div x-data="{
                    search: '{{ isset($aluno) ? $aluno->pessoa->nome : '' }}',
                    selected: '{{ old('pessoa_id', $aluno->pessoa_id ?? '') }}',
                    open: false,
                    pessoas: {{ $pessoas->map(fn($p) => ['id' => $p->id, 'nome' => $p->nome, 'cpf' => $p->cpf])->toJson() }},
                    get filtered() {
                        if (!this.search) return this.pessoas;
                        return this.pessoas.filter(p => p.nome.toLowerCase().includes(this.search.toLowerCase()));
                    }
                }" class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pessoa *</label>
                    <input type="hidden" name="pessoa_id" x-model="selected">
                    <input type="text" x-model="search" @focus="open = true" @click.away="open = false"
                           placeholder="Buscar pessoa..."
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    <div x-show="open && filtered.length > 0" x-cloak
                         class="absolute z-10 mt-1 w-full bg-white border rounded-lg shadow-lg max-h-48 overflow-y-auto">
                        <template x-for="pessoa in filtered" :key="pessoa.id">
                            <button type="button"
                                    @click="selected = pessoa.id; search = pessoa.nome; open = false"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50 flex justify-between">
                                <span x-text="pessoa.nome"></span>
                                <span class="text-gray-400 text-xs" x-text="pessoa.cpf"></span>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- RA --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">RA</label>
                    <input type="text" name="ra" value="{{ old('ra', $aluno->ra ?? '') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                </div>

                {{-- Forma Ingresso --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Forma de Ingresso</label>
                    <select name="forma_ingresso_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                        <option value="">Selecione</option>
                        @foreach($formasIngresso as $forma)
                            <option value="{{ $forma->id }}" {{ old('forma_ingresso_id', $aluno->forma_ingresso_id ?? '') == $forma->id ? 'selected' : '' }}>
                                {{ $forma->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Data Ingresso --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data de Ingresso</label>
                    <input type="date" name="data_ingresso"
                           value="{{ old('data_ingresso', isset($aluno) && $aluno->data_ingresso ? $aluno->data_ingresso->format('Y-m-d') : '') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                </div>

                {{-- Ativo --}}
                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="ativo" value="1"
                               {{ old('ativo', $aluno->ativo ?? true) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="text-sm font-medium text-gray-700">Ativo</span>
                    </label>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 mt-6 pt-4 border-t">
                <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-primary-700 transition flex items-center gap-2">
                    <i class="fa-solid fa-check"></i> Salvar
                </button>
                <a href="{{ route('alunos.index') }}" class="px-6 py-2 border rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                    Cancelar
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
