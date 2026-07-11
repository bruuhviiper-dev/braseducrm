@extends('layouts.app')
@section('title', 'Funil de Vendas')

@section('content')
@php
    $totalAndamento = $funil->etapas->sum(fn ($e) => $e->oportunidades->count());
    $totalExibido = $totalAndamento + $ganhas->count() + $perdidas->count();
@endphp
<div x-data="funilBoard()" class="-m-2">

    {{-- Cabeçalho e barra de filtros (doc CRM) --}}
    <div class="flex flex-wrap items-center justify-between gap-3 bg-white border-b px-4 py-3">
        <div class="flex items-center gap-2">
            <span class="text-gray-400 text-sm">110</span>
            <h1 class="text-lg font-bold text-gray-800">Funil de Oportunidades (CRM)</h1>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" x-model="busca" placeholder="Buscar..." class="pl-8 pr-3 py-2 border rounded-full text-sm w-56 focus:ring-2 focus:ring-blue-400 outline-none">
            </div>
            <form method="GET" class="flex items-center gap-2">
                <div class="border rounded-lg px-2 py-1">
                    <label class="block text-[10px] text-gray-400 leading-none">Situação</label>
                    <select name="situacao" onchange="this.form.submit()" class="text-sm font-medium text-gray-700 outline-none bg-transparent">
                        <option value="andamento" @selected($situacao === 'andamento')>Em Andamento</option>
                        <option value="ganho" @selected($situacao === 'ganho')>Ganho</option>
                        <option value="perda" @selected($situacao === 'perda')>Perda</option>
                        <option value="todas" @selected($situacao === 'todas')>Todas</option>
                    </select>
                </div>
                <div class="border rounded-lg px-2 py-1">
                    <label class="block text-[10px] text-gray-400 leading-none">Funil<span class="text-red-500">*</span></label>
                    <select onchange="window.location='{{ url('crm/funil') }}/'+this.value+'?situacao={{ $situacao }}'" class="text-sm font-medium text-gray-700 outline-none bg-transparent max-w-[180px]">
                        @foreach($funis as $f)
                        <option value="{{ $f->id }}" @selected($f->id === $funil->id)>{{ mb_strtoupper($f->nome) }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <a href="{{ route('crm.oportunidades.index') }}" class="p-2 border rounded-lg text-gray-500 hover:bg-gray-50" title="Lista de oportunidades"><i class="fa-solid fa-users"></i></a>
            <a href="{{ route('crm.tags.index') }}" class="p-2 border rounded-lg text-gray-500 hover:bg-gray-50" title="Tags"><i class="fa-solid fa-tags"></i></a>
            <button onclick="window.location.reload()" class="p-2 border rounded-lg text-gray-500 hover:bg-gray-50" title="Atualizar"><i class="fa-solid fa-arrows-rotate"></i></button>
        </div>
    </div>

    {{-- Quadro Kanban: etapas do funil + colunas fixas GANHO e PERDA --}}
    <div class="flex overflow-x-auto bg-white" style="min-height: calc(100vh - 180px);">
        @foreach($funil->etapas as $etapa)
            @include('crm.funil._coluna', [
                'nome' => mb_strtoupper($etapa->nome),
                'icone' => null,
                'ops' => $etapa->oportunidades,
                'dropEtapa' => $etapa->id,
                'dropTipo' => 'etapa',
            ])
        @endforeach
        @include('crm.funil._coluna', ['nome' => 'GANHO', 'icone' => 'fa-thumbs-up text-green-500', 'ops' => $ganhas, 'dropEtapa' => null, 'dropTipo' => 'ganho', 'totalReal' => $totais['ganha']])
        @include('crm.funil._coluna', ['nome' => 'PERDA', 'icone' => null, 'ops' => $perdidas, 'dropEtapa' => null, 'dropTipo' => 'perda', 'totalReal' => $totais['perdida']])
    </div>

    {{-- Barra inferior: volume + visualização P M G (doc CRM) --}}
    <div class="flex items-center justify-between bg-white border-t px-4 py-2 text-sm text-gray-500">
        <span>Exibindo <span x-text="visiveis"></span> de {{ $totalExibido }} oportunidades</span>
        <div class="flex items-center gap-2">
            <span class="text-xs text-gray-400"><i class="fa-regular fa-circle-question mr-1"></i>Ajuda</span>
            <span class="text-xs text-gray-400 ml-3">Visualização:</span>
            @foreach(['P', 'M', 'G'] as $t)
            <button @click="viz='{{ $t }}'; localStorage.setItem('one_funil_viz', '{{ $t }}')"
                    :class="viz==='{{ $t }}' ? 'bg-gray-700 text-white' : 'text-gray-500 hover:bg-gray-100'"
                    class="w-7 h-7 rounded-full text-xs font-bold">{{ $t }}</button>
            @endforeach
        </div>
    </div>

    {{-- Menu flutuante em cascata (doc CRM: atalhos de produtividade) --}}
    <div class="fixed bottom-16 right-4 z-40 flex flex-col items-center gap-2">
        <template x-if="fabAberto">
            <div class="flex flex-col items-center gap-2">
                <a href="{{ route('crm.oportunidades.create') }}" class="w-10 h-10 rounded-full bg-pink-500 text-white flex items-center justify-center shadow-lg hover:scale-105 transition" title="Cadastrar um lead manual"><i class="fa-solid fa-user-plus"></i></a>
                <a href="{{ route('crm.oportunidades.index') }}" class="w-10 h-10 rounded-full bg-purple-500 text-white flex items-center justify-center shadow-lg hover:scale-105 transition" title="Registrar anotação de ligação (abra o card)"><i class="fa-solid fa-phone"></i></a>
                <a href="{{ route('calendario.index') }}" class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg hover:scale-105 transition" title="Agendar visita ou retorno"><i class="fa-regular fa-calendar-plus"></i></a>
                <a href="{{ route('comunicacao.mensagens.index') }}" class="w-10 h-10 rounded-full bg-teal-600 text-white flex items-center justify-center shadow-lg hover:scale-105 transition" title="Disparar um e-mail"><i class="fa-regular fa-envelope"></i></a>
                <button @click="document.querySelector('[x-model=busca]')?.focus()" class="w-10 h-10 rounded-full bg-gray-500 text-white flex items-center justify-center shadow-lg hover:scale-105 transition" title="Filtrar"><i class="fa-solid fa-filter"></i></button>
            </div>
        </template>
        <button @click="fabAberto = !fabAberto" class="w-12 h-12 rounded-2xl bg-blue-500 text-white flex items-center justify-center shadow-xl hover:bg-blue-600 transition text-xl">
            <i class="fa-solid" :class="fabAberto ? 'fa-xmark' : 'fa-plus'"></i>
        </button>
    </div>

    {{-- Modal de perda ao arrastar para PERDA (doc: o sistema pede um Motivo da Perda) --}}
    <div x-show="perdaOp" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" @click.self="perdaOp = null">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-1">Marcar como Perdida</h3>
            <p class="text-xs text-gray-400 mb-4">O motivo é obrigatório e alimenta os relatórios de perda.</p>
            <form method="POST" :action="'{{ url('crm/oportunidades') }}/' + perdaOp + '/perder'" class="space-y-3">
                @csrf
                <select name="motivo_perda_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Motivo da perda...</option>
                    @foreach($motivosPerda as $mp)
                    <option value="{{ $mp->id }}">{{ $mp->nome }}</option>
                    @endforeach
                </select>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="perdaOp = null" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg text-sm font-semibold">Confirmar Perda</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function funilBoard() {
    return {
        busca: '',
        viz: localStorage.getItem('one_funil_viz') || 'G',
        fabAberto: false,
        perdaOp: null,
        draggedId: null,
        arrastando: false,
        visiveis: {{ $totalExibido }},

        atualizarVisiveis() {
            this.$nextTick(() => {
                this.visiveis = document.querySelectorAll('[data-card]:not([style*="display: none"])').length;
            });
        },
        combina(texto) {
            return !this.busca || texto.toLowerCase().includes(this.busca.toLowerCase());
        },
        abrir(id) {
            if (this.arrastando) return;
            window.location = '{{ url('crm/oportunidades') }}/' + id + '/edit';
        },
        setEstrelas(id, n, ev) {
            ev.stopPropagation();
            fetch('{{ url('crm/oportunidades') }}/' + id + '/estrelas', {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ estrelas: n })
            }).then(() => {
                document.querySelectorAll('[data-star="' + id + '"]').forEach((el, i) => {
                    el.classList.toggle('text-amber-400', i < n);
                    el.classList.toggle('text-gray-300', i >= n);
                });
            });
        },
        onDragStart(ev, id) { this.draggedId = id; this.arrastando = true; ev.dataTransfer.effectAllowed = 'move'; ev.dataTransfer.setData('text/plain', id); ev.target.classList.add('opacity-50'); },
        onDragEnd(ev) { ev.target.classList.remove('opacity-50'); setTimeout(() => this.arrastando = false, 150); },
        onDrop(ev, tipo, etapaId) {
            ev.preventDefault();
            const id = ev.dataTransfer.getData('text/plain');
            if (!id) return;
            if (tipo === 'ganho') {
                // Doc CRM: arrastar para GANHO ativa o processo de matrícula (wizard em 4 passos)
                if (confirm('Dar como GANHO e iniciar a matrícula do aluno?')) {
                    window.location = '{{ route('academico.matriculas.wizard') }}?oportunidade=' + id;
                }
                return;
            }
            if (tipo === 'perda') { this.perdaOp = id; return; }
            fetch('{{ url('crm/oportunidades') }}/' + id + '/mover-etapa', {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ etapa_funil_id: etapaId })
            }).then(r => { if (r.ok) window.location.reload(); });
        },
    };
}
</script>
@endpush
@endsection
