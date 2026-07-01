@extends('layouts.app')
@section('title', $gerador ? 'Editar Gerador' : 'Gerador de Avaliações')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">241</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $gerador ? 'Editar Gerador' : 'Gerador de Avaliações' }}</h1>
        </div>
        <form action="{{ $gerador ? route('ead.geradores.update', $gerador) : route('ead.geradores.store') }}" method="POST" class="p-6 space-y-4"
              x-data="geradorForm(@js($gerador?->parametros->map(fn($p)=>['tag_questao_id'=>$p->tag_questao_id,'quantidade'=>$p->quantidade])->values() ?? []))">
            @csrf
            @if($gerador) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                <input type="text" name="descricao" value="{{ old('descricao', $gerador->descricao ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="bg-gray-50 border rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-700">Parâmetros de seleção de questões</h2>
                        <p class="text-xs text-gray-500">Total de questões avulsas ativas disponíveis: <strong>{{ $totalQuestoes }}</strong></p>
                    </div>
                    <button type="button" @click="add()" class="px-3 py-1.5 bg-primary-600 text-white rounded-lg text-xs font-medium hover:bg-primary-700"><i class="fa-solid fa-plus mr-1"></i> Adicionar</button>
                </div>

                <template x-for="(p, i) in parametros" :key="i">
                    <div class="flex gap-2 items-end mb-2">
                        <div class="flex-1">
                            <label class="block text-xs text-gray-500 mb-1">Tag (categoria)</label>
                            <select :name="`parametros[${i}][tag_questao_id]`" x-model="p.tag_questao_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                                <option value="">Todas as questões</option>
                                @foreach($tags as $t)<option value="{{ $t->id }}">{{ $t->nome }}</option>@endforeach
                            </select>
                        </div>
                        <div class="w-32">
                            <label class="block text-xs text-gray-500 mb-1">Quantidade</label>
                            <input type="number" min="1" :name="`parametros[${i}][quantidade]`" x-model="p.quantidade" class="w-full border rounded-lg px-3 py-2 text-sm">
                        </div>
                        <button type="button" @click="remove(i)" class="p-2 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </template>
                <p x-show="parametros.length === 0" class="text-xs text-gray-400 py-2">Nenhum parâmetro adicionado. A avaliação sorteará questões conforme os grupos definidos aqui.</p>
                <p class="text-xs text-gray-500 mt-2">Total de questões que serão sorteadas: <strong x-text="total()"></strong></p>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('ead.geradores.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
function geradorForm(iniciais) {
    return {
        parametros: (iniciais && iniciais.length) ? iniciais.map(p => ({ tag_questao_id: p.tag_questao_id ?? '', quantidade: p.quantidade ?? 1 })) : [],
        add() { this.parametros.push({ tag_questao_id: '', quantidade: 1 }); },
        remove(i) { this.parametros.splice(i, 1); },
        total() { return this.parametros.reduce((s, p) => s + (parseInt(p.quantidade) || 0), 0); },
    };
}
</script>
@endsection
