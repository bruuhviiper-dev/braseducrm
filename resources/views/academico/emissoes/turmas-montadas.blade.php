@extends('layouts.app')
@section('title', 'Emissão de Turmas Montadas')

@section('content')
{{-- 184 Emissão de Turmas Montadas — construtor de relatório dinâmico (padrão EDUQ) --}}
<div x-data="emissaoTurmas()" class="w-full">

    <div class="flex items-start justify-between flex-wrap gap-3 mb-4">
        <div class="flex items-start gap-2">
            <span class="text-base font-semibold text-gray-400 mt-0.5">184</span>
            <div>
                <h1 class="text-xl font-bold text-gray-800">Emissão de Turmas Montadas</h1>
                <p class="text-xs text-gray-400">Acadêmico › Turmas</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" @click="novoLayout = true" class="w-9 h-9 rounded-lg border border-cyan-300 text-cyan-600 hover:bg-cyan-50" title="Salvar layout atual"><i class="fa-solid fa-plus"></i></button>
            <div class="border rounded-lg px-2 py-1 min-w-[200px]">
                <label class="block text-[10px] text-gray-400 leading-none">Layouts</label>
                <select x-model="layoutId" @change="aplicarLayout()" class="text-sm font-medium text-gray-700 outline-none bg-transparent w-full">
                    <option value="">Padrão</option>
                    @foreach($layouts as $l)<option value="{{ $l->id }}" @selected($layoutAtual && $layoutAtual->id === $l->id)>{{ $l->nome }}@if($l->padrao) (padrão)@endif</option>@endforeach
                </select>
            </div>
            @if($layoutAtual)
            <form method="POST" action="{{ route('academico.emissoes.turmas-montadas.layout.excluir', $layoutAtual) }}" onsubmit="return confirm('Excluir este layout?')">@csrf @method('DELETE')
                <button class="w-9 h-9 rounded-lg border border-red-200 text-red-500 hover:bg-red-50" title="Excluir layout"><i class="fa-regular fa-trash-can"></i></button>
            </form>
            @endif
        </div>
    </div>

    @if(session('success'))<div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>@endif

    <form method="GET" action="{{ route('academico.emissoes.turmas-montadas.emitir') }}" target="_blank" class="bg-white rounded-xl border">
        <template x-for="c in colunas" :key="c"><input type="hidden" name="colunas[]" :value="c"></template>

        <div class="flex overflow-x-auto border-b text-sm">
            @foreach(['filtros' => 'Filtros', 'colunas' => 'Colunas', 'pagina' => 'Layout de Página'] as $k => $tab)
            <button type="button" @click="aba = '{{ $k }}'" :class="aba === '{{ $k }}' ? 'border-cyan-500 text-cyan-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-2.5 border-b-2 whitespace-nowrap">{{ $tab }}</button>
            @endforeach
        </div>

        {{-- Filtros --}}
        <div x-show="aba === 'filtros'" class="p-5 space-y-4">
            <div class="grid md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Turma</label>
                    <select name="turmas[]" multiple size="4" class="w-full border rounded-lg px-3 py-2 text-sm">@foreach($turmas as $t)<option value="{{ $t->id }}">{{ $t->nome }}</option>@endforeach</select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Período Letivo</label>
                    <select name="periodos[]" multiple size="4" class="w-full border rounded-lg px-3 py-2 text-sm">@foreach($periodos as $p)<option value="{{ $p->id }}">{{ $p->nome }}</option>@endforeach</select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Módulo</label>
                    <select name="modulos[]" multiple size="4" class="w-full border rounded-lg px-3 py-2 text-sm">@foreach($modulos as $m)<option value="{{ $m->id }}">{{ $m->nome }}</option>@endforeach</select>
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-3 border-t pt-4">
                <div><label class="block text-[11px] text-gray-400 mb-0.5">Período da turma — Início</label><input type="date" name="inicio" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
                <div><label class="block text-[11px] text-gray-400 mb-0.5">Período da turma — Fim</label><input type="date" name="fim" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
            </div>
            <div class="space-y-2 border-t pt-4">
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer"><input type="checkbox" name="inativos" value="1" class="rounded text-cyan-500">Incluir inativos?</label>
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer"><input type="checkbox" name="somente_finalizadas" value="1" class="rounded text-cyan-500">Filtrar somente turmas finalizadas?</label>
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer"><input type="checkbox" name="somente_ativas" value="1" class="rounded text-cyan-500">Filtrar somente turmas ativas?</label>
            </div>
        </div>

        {{-- Colunas --}}
        <div x-show="aba === 'colunas'" x-cloak class="p-5">
            <p class="text-xs text-gray-400 mb-3">Selecione as colunas para emissão.</p>
            <div class="grid md:grid-cols-2 gap-2">
                @foreach($catalogo as $chave => $rotulo)
                <label class="flex items-center gap-2 text-sm border rounded-lg px-3 py-2 cursor-pointer">
                    <input type="checkbox" value="{{ $chave }}" :checked="colunas.includes('{{ $chave }}')" @change="toggleColuna('{{ $chave }}')" class="rounded text-cyan-500">{{ $rotulo }}
                </label>
                @endforeach
            </div>
        </div>

        {{-- Layout de Página --}}
        <div x-show="aba === 'pagina'" x-cloak class="p-5 grid md:grid-cols-2 gap-4">
            <div><label class="block text-xs text-gray-500 mb-1">Orientação</label><select name="orientacao" class="w-full border rounded-lg px-3 py-2 text-sm"><option value="landscape">Paisagem</option><option value="portrait">Retrato</option></select></div>
            <div><label class="block text-xs text-gray-500 mb-1">Papel</label><select name="papel" class="w-full border rounded-lg px-3 py-2 text-sm"><option value="a4">A4</option><option value="letter">Carta</option><option value="legal">Ofício</option></select></div>
        </div>

        <div class="flex justify-end gap-2 px-5 py-4 border-t bg-gray-50 rounded-b-xl">
            <button type="submit" name="formato" value="pdf" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700"><i class="fa-solid fa-file-pdf mr-1"></i>PDF</button>
            <button type="submit" name="formato" value="csv" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-semibold hover:bg-green-700"><i class="fa-solid fa-file-csv mr-1"></i>CSV</button>
            <button type="submit" name="formato" value="xlsx" class="px-4 py-2 bg-green-700 text-white rounded-lg text-sm font-semibold hover:bg-green-800"><i class="fa-solid fa-file-excel mr-1"></i>XLSX</button>
        </div>
    </form>

    <div x-show="novoLayout" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" @click.self="novoLayout = false">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-1">Salvar layout</h3>
            <p class="text-xs text-gray-400 mb-4">Salva as colunas selecionadas como um layout reutilizável.</p>
            <form method="POST" action="{{ route('academico.emissoes.turmas-montadas.layout') }}" class="space-y-3">
                @csrf
                <template x-for="c in colunas" :key="'l' + c"><input type="hidden" name="colunas[]" :value="c"></template>
                <input type="text" name="nome" required placeholder="Nome do layout" class="w-full border rounded-lg px-3 py-2 text-sm">
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer"><input type="checkbox" name="padrao" value="1" class="rounded text-cyan-500">Definir como layout padrão</label>
                <div class="flex justify-end gap-2 pt-1">
                    <button type="button" @click="novoLayout = false" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg text-sm font-semibold">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
const LAYOUTS_184 = @json($layouts->map(fn ($l) => ['id' => $l->id, 'colunas' => $l->colunas]));
function emissaoTurmas() {
    return {
        aba: 'filtros', novoLayout: false,
        layoutId: '{{ $layoutAtual->id ?? '' }}',
        colunas: @json(array_values($colunasSel)),
        toggleColuna(c) { this.colunas.includes(c) ? this.colunas = this.colunas.filter(x => x !== c) : this.colunas.push(c); },
        aplicarLayout() { const l = LAYOUTS_184.find(x => String(x.id) === String(this.layoutId)); if (l) this.colunas = l.colunas.slice(); },
    };
}
</script>
@endpush
@endsection
