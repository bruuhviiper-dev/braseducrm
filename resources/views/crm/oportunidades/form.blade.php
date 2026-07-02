@extends('layouts.app')
@section('title', ($oportunidade ?? null) ? 'Editar Oportunidade' : 'Manutenção de Oportunidades')

@section('content')
@php
    $tagsSel = ($oportunidade ?? null) ? $oportunidade->tags->pluck('id')->all() : [];
@endphp
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <a href="{{ route('crm.oportunidades.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">109</span>
            <div>
                <h1 class="text-lg font-semibold text-gray-800">Manutenção de Oportunidades <span class="text-gray-400 font-normal">(CRM)</span></h1>
                <p class="text-xs text-gray-400">CRM › Oportunidades</p>
            </div>
        </div>

        <form method="POST" action="{{ ($oportunidade ?? null) ? route('crm.oportunidades.update', $oportunidade) : route('crm.oportunidades.store') }}" class="p-6" x-data="oportunidadeForm()">
            @csrf
            @if($oportunidade ?? null) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-4">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Qualificação</label>
                    <select name="qualificacao" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach(['quente'=>'🔥 Quente','morno'=>'🌤️ Morno','frio'=>'❄️ Frio'] as $val=>$lbl)
                        <option value="{{ $val }}" @selected(old('qualificacao', $oportunidade->qualificacao ?? '')===$val)>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Interessado <span class="text-red-500">*</span></label>
                    <select name="interessado_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($interessados as $i)<option value="{{ $i->id }}" @selected(old('interessado_id', $oportunidade->interessado_id ?? '')==$i->id)>{{ $i->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Origem (de onde veio)</label>
                    <select name="origem_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($origens as $o)<option value="{{ $o->id }}" @selected(old('origem_id', $oportunidade->origem_id ?? '')==$o->id)>{{ $o->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Responsável</label>
                    <select name="consultor_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($consultores as $c)<option value="{{ $c->id }}" @selected(old('consultor_id', $oportunidade->consultor_id ?? '')==$c->id)>{{ $c->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Funil <span class="text-red-500">*</span></label>
                    <select name="funil_id" id="funil_id" required x-model="funilId" @change="loadEtapas()" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($funis as $f)<option value="{{ $f->id }}" @selected(old('funil_id', $oportunidade->funil_id ?? '')==$f->id)>{{ $f->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Etapa <span class="text-red-500">*</span></label>
                    <select name="etapa_funil_id" id="etapa_funil_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($etapas as $e)<option value="{{ $e->id }}" data-funil="{{ $e->funil_id }}" @selected(old('etapa_funil_id', $oportunidade->etapa_funil_id ?? '')==$e->id)>{{ $e->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quem indicou?</label>
                    <select name="indicacao_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($indicacoes as $ind)<option value="{{ $ind->id }}" @selected(old('indicacao_id', $oportunidade->indicacao_id ?? '')==$ind->id)>{{ $ind->nome_indicado ?? ('Indicação #'.$ind->id) }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Produtos e serviços (Curso)</label>
                    <select name="curso_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($cursos as $cur)<option value="{{ $cur->id }}" @selected(old('curso_id', $oportunidade->curso_id ?? '')==$cur->id)>{{ $cur->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                    <input type="text" name="titulo" value="{{ old('titulo', $oportunidade->titulo ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valor (R$)</label>
                    <input type="number" step="0.01" min="0" name="valor" value="{{ old('valor', $oportunidade->valor ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Situação <span class="text-red-500">*</span></label>
                    <select name="situacao" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        @foreach(['aberta'=>'Aberta','ganha'=>'Ganha','perdida'=>'Perdida','pausada'=>'Pausada'] as $val=>$lbl)
                        <option value="{{ $val }}" @selected(old('situacao', $oportunidade->situacao ?? 'aberta')===$val)>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Previsão de Fechamento</label>
                    <input type="date" name="data_previsao_fechamento" value="{{ old('data_previsao_fechamento', optional($oportunidade->data_previsao_fechamento ?? null)->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
            </div>

            {{-- Tags --}}
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                <div class="flex flex-wrap gap-2 border rounded-lg p-3">
                    @forelse($tagsList as $t)
                    <label class="flex items-center gap-1.5 text-sm border rounded-full px-2.5 py-1 cursor-pointer hover:bg-gray-50">
                        <input type="checkbox" name="tags[]" value="{{ $t->id }}" @checked(in_array($t->id, old('tags', $tagsSel))) class="rounded text-primary-600">
                        <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $t->cor ?? '#94a3b8' }}"></span>
                        {{ $t->nome }}
                    </label>
                    @empty
                    <span class="text-xs text-gray-400">Nenhuma tag cadastrada.</span>
                    @endforelse
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Motivação do interesse</label>
                    <textarea name="motivacao_interesse" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('motivacao_interesse', $oportunidade->motivacao_interesse ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observação</label>
                    <textarea name="observacoes" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('observacoes', $oportunidade->observacoes ?? '') }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t">
                <a href="{{ route('crm.oportunidades.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function oportunidadeForm() {
    return {
        funilId: '{{ old('funil_id', $oportunidade->funil_id ?? '') }}',
        loadEtapas() {
            const select = document.getElementById('etapa_funil_id');
            select.querySelectorAll('option[data-funil]').forEach(o => {
                if (this.funilId && o.dataset.funil !== this.funilId) { o.style.display = 'none'; if (o.selected) o.selected = false; }
                else { o.style.display = ''; }
            });
        },
        init() { this.loadEtapas(); }
    };
}
</script>
@endpush
@endsection
