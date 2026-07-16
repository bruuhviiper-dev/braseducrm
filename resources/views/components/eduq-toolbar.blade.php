{{-- Barra de ações inferior estilo EDUQ: aparece ao selecionar uma linha (usa o escopo Alpine listaEduq()) --}}
<div x-show="sel !== null" x-cloak
     x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0"
     class="fixed bottom-6 left-1/2 -translate-x-1/2 z-40 flex items-center gap-1 bg-white border border-gray-200 shadow-2xl rounded-full pl-4 pr-1.5 py-1.5">
    <span class="text-xs text-gray-400 mr-1">1 selecionado</span>
    <a :href="editUrl" class="flex items-center gap-1.5 px-4 py-2 rounded-full text-sm text-gray-700 hover:bg-gray-100"><i class="fa-solid fa-pen text-cyan-600"></i> Editar</a>
    <template x-if="delUrl">
        <form :action="delUrl" method="POST" onsubmit="return confirm('Excluir este registro?')" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="flex items-center gap-1.5 px-4 py-2 rounded-full text-sm text-red-600 hover:bg-red-50"><i class="fa-regular fa-trash-can"></i> Excluir</button>
        </form>
    </template>
    <button type="button" @click="sel = null" class="w-8 h-8 rounded-full text-gray-400 hover:bg-gray-100 flex items-center justify-center" title="Fechar"><i class="fa-solid fa-xmark"></i></button>
</div>

@pushOnce('scripts')
<script>
function listaEduq() {
    return {
        sel: null, editUrl: '', delUrl: '',
        toggle(id, edit, del) {
            if (this.sel === id) { this.sel = null; return; }
            this.sel = id; this.editUrl = edit; this.delUrl = del || '';
        },
    };
}
</script>
@endPushOnce
