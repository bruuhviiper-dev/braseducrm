@props([
    'codigo', 'titulo', 'breadcrumb' => null,
    'emitirRoute',                 // rota GET de emissão
    'funcao',                      // código da função (p/ layouts)
    'catalogo' => [],              // chave => rótulo
    'colunasSel' => [],            // colunas iniciais
    'layouts' => null,             // coleção de layouts
    'layoutAtual' => null,
    'abas' => ['filtros' => 'Filtros'], // abas extras à esquerda de Colunas
    'semColunas' => false,         // esconde a aba Colunas (relatórios de layout fixo)
    'semPagina' => false,          // esconde Layout de Página
])
{{-- Construtor de relatório dinâmico reutilizável (padrão EDUQ): Layouts salvos, abas, aba Colunas, export PDF/CSV/XLSX --}}
<div x-data="reportBuilder(@js(array_values($colunasSel)), @js(($layouts ?? collect())->map(fn ($l) => ['id' => $l->id, 'colunas' => $l->colunas])->values()), '{{ $layoutAtual->id ?? '' }}')" class="w-full">

    <div class="flex items-start justify-between flex-wrap gap-3 mb-4">
        <div class="flex items-start gap-2">
            <span class="text-base font-semibold text-gray-400 mt-0.5">{{ $codigo }}</span>
            <div>
                <h1 class="text-xl font-bold text-gray-800">{{ $titulo }}</h1>
                @if($breadcrumb)<p class="text-xs text-gray-400">{{ $breadcrumb }}</p>@endif
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" @click="novoLayout = true" class="w-9 h-9 rounded-lg border border-cyan-300 text-cyan-600 hover:bg-cyan-50" title="Salvar layout atual"><i class="fa-solid fa-plus"></i></button>
            <div class="border rounded-lg px-2 py-1 min-w-[200px]">
                <label class="block text-[10px] text-gray-400 leading-none">Layouts</label>
                <select x-model="layoutId" @change="aplicarLayout()" class="text-sm font-medium text-gray-700 outline-none bg-transparent w-full">
                    <option value="">Padrão</option>
                    @foreach(($layouts ?? []) as $l)<option value="{{ $l->id }}" @selected($layoutAtual && $layoutAtual->id === $l->id)>{{ $l->nome }}{{ $l->padrao ? ' (padrão)' : '' }}{{ ($l->compartilhado ?? false) ? ' (compartilhado)' : '' }}</option>@endforeach
                </select>
            </div>
            @if($layoutAtual)
            <form method="POST" action="{{ route('academico.emissoes.layout.excluir', $layoutAtual) }}" onsubmit="return confirm('Excluir este layout?')">@csrf @method('DELETE')
                <button class="w-9 h-9 rounded-lg border border-red-200 text-red-500 hover:bg-red-50" title="Excluir layout"><i class="fa-regular fa-trash-can"></i></button>
            </form>
            @endif
        </div>
    </div>

    @if(session('success'))<div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>@endif

    <form method="GET" action="{{ $emitirRoute }}" target="_blank" class="bg-white rounded-xl border">
        <template x-for="c in colunas" :key="c"><input type="hidden" name="colunas[]" :value="c"></template>

        {{-- Abas --}}
        <div class="flex overflow-x-auto border-b text-sm">
            @foreach($abas as $k => $rot)
            <button type="button" @click="aba = '{{ $k }}'" :class="aba === '{{ $k }}' ? 'border-cyan-500 text-cyan-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-2.5 border-b-2 whitespace-nowrap">{{ $rot }}</button>
            @endforeach
            @unless($semColunas)
            <button type="button" @click="aba = 'colunas'" :class="aba === 'colunas' ? 'border-cyan-500 text-cyan-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-2.5 border-b-2 whitespace-nowrap">Colunas</button>
            @endunless
            @unless($semPagina)
            <button type="button" @click="aba = 'pagina'" :class="aba === 'pagina' ? 'border-cyan-500 text-cyan-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-2.5 border-b-2 whitespace-nowrap">Layout de Página (Somente PDF)</button>
            @endunless
        </div>

        {{-- Slot: conteúdo das abas de filtro (cada uma envolvida em x-show pela chave) --}}
        {{ $slot }}

        {{-- Aba Colunas --}}
        @unless($semColunas)
        <div x-show="aba === 'colunas'" x-cloak class="p-5">
            <p class="text-xs text-gray-400 mb-3">Selecione as colunas para emissão.</p>
            <div class="grid md:grid-cols-2 gap-2">
                @foreach($catalogo as $chave => $rotulo)
                <label class="flex items-center gap-2 text-sm border rounded-lg px-3 py-2 cursor-pointer">
                    <input type="checkbox" value="{{ $chave }}" :checked="colunas.includes('{{ $chave }}')" @change="toggleColuna('{{ $chave }}')" class="rounded text-cyan-500">{{ $rotulo }}
                </label>
                @endforeach
            </div>
            <p class="text-xs text-gray-500 mt-3"><span x-text="colunas.length"></span> coluna(s): <span class="font-mono text-gray-400" x-text="colunas.join(', ')"></span></p>
        </div>
        @endunless

        {{-- Aba Layout de Página --}}
        @unless($semPagina)
        <div x-show="aba === 'pagina'" x-cloak class="p-5 grid md:grid-cols-2 gap-4">
            <div><label class="block text-xs text-gray-500 mb-1">Orientação</label><select name="orientacao" class="w-full border rounded-lg px-3 py-2 text-sm"><option value="landscape">Paisagem</option><option value="portrait">Retrato</option></select></div>
            <div><label class="block text-xs text-gray-500 mb-1">Papel</label><select name="papel" class="w-full border rounded-lg px-3 py-2 text-sm"><option value="a4">A4</option><option value="letter">Carta</option><option value="legal">Ofício</option></select></div>
        </div>
        @endunless

        {{-- Botões de exportação FLUTUANTES no canto inferior direito (padrão EDUQ) --}}
        <div class="fixed bottom-6 right-6 z-40 flex items-center gap-2.5">
            <button type="submit" name="formato" value="pdf" class="px-5 py-2.5 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 shadow-lg shadow-red-600/25"><i class="fa-solid fa-file-pdf mr-1.5"></i>PDF</button>
            <button type="submit" name="formato" value="csv" class="px-5 py-2.5 bg-green-600 text-white rounded-lg text-sm font-semibold hover:bg-green-700 shadow-lg shadow-green-600/25"><i class="fa-solid fa-file-csv mr-1.5"></i>CSV</button>
            <button type="submit" name="formato" value="xlsx" class="px-5 py-2.5 bg-green-700 text-white rounded-lg text-sm font-semibold hover:bg-green-800 shadow-lg shadow-green-700/25"><i class="fa-solid fa-file-excel mr-1.5"></i>XLSX</button>
        </div>
    </form>

    {{-- Modal salvar layout (Descrição / É o Layout Padrão? / usado por outros operadores?) --}}
    <div x-show="novoLayout" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" @click.self="novoLayout = false">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-1">Salvar layout</h3>
            <p class="text-xs text-gray-400 mb-4">Salva as colunas selecionadas como um layout reutilizável.</p>
            <form method="POST" action="{{ route('academico.emissoes.layout') }}" class="space-y-3">
                @csrf
                <input type="hidden" name="funcao_codigo" value="{{ $funcao }}">
                <template x-for="c in colunas" :key="'l' + c"><input type="hidden" name="colunas[]" :value="c"></template>
                <input type="text" name="nome" required placeholder="Descrição do layout" class="w-full border rounded-lg px-3 py-2 text-sm">
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer"><input type="checkbox" name="padrao" value="1" class="rounded text-cyan-500">É o Layout Padrão?</label>
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer"><input type="checkbox" name="compartilhado" value="1" class="rounded text-cyan-500">Layout pode ser usado por outros operadores?</label>
                <div class="flex justify-end gap-2 pt-1">
                    <button type="button" @click="novoLayout = false" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg text-sm font-semibold">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@pushOnce('scripts')
<script>
function reportBuilder(colunasIni, layouts, layoutId) {
    return {
        aba: 'filtros',
        novoLayout: false,
        layoutId: layoutId,
        colunas: colunasIni,
        _layouts: layouts,
        toggleColuna(c) { this.colunas.includes(c) ? this.colunas = this.colunas.filter(x => x !== c) : this.colunas.push(c); },
        aplicarLayout() { const l = this._layouts.find(x => String(x.id) === String(this.layoutId)); if (l) this.colunas = l.colunas.slice(); },
    };
}
</script>
@endPushOnce
