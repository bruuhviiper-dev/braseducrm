@extends('layouts.app')
@section('title', 'Funil de Oportunidades')

@section('content')
<div class="mb-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <span class="bg-primary-50 text-primary-600 font-bold px-2 py-0.5 rounded text-sm">110</span>
        <h1 class="text-lg font-semibold">Funil de Oportunidades (CRM)</h1>
        <span class="text-gray-500">- {{ $funil->nome }}</span>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('crm.funil.index') }}" class="px-3 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition flex items-center gap-1">
            <i class="fa-solid fa-arrow-left mr-1"></i> Voltar
        </a>
        <button class="px-3 py-2 border rounded-lg text-sm hover:bg-gray-50 transition">
            <i class="fa-solid fa-file-import mr-1"></i> Importar
        </button>
        <button class="px-3 py-2 border rounded-lg text-sm hover:bg-gray-50 transition">
            <i class="fa-solid fa-file-export mr-1"></i> Exportar
        </button>
        <button onclick="window.location.reload()" class="px-3 py-2 border rounded-lg text-sm hover:bg-gray-50 transition">
            <i class="fa-solid fa-arrows-rotate mr-1"></i> Recarregar
        </button>
        <button class="px-3 py-2 border rounded-lg text-sm hover:bg-gray-50 transition">
            <i class="fa-solid fa-filter mr-1"></i> Filtrar
        </button>
    </div>
</div>

{{-- Summary bar --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
    @php
        $totalOps = $funil->etapas->sum(fn($e) => $e->oportunidades->count());
        $totalValor = $funil->etapas->sum(fn($e) => $e->oportunidades->sum('valor'));
        $ganhas = $funil->etapas->sum(fn($e) => $e->oportunidades->where('situacao', 'ganha')->count());
        $abertas = $funil->etapas->sum(fn($e) => $e->oportunidades->where('situacao', 'aberta')->count());
    @endphp
    <div class="bg-white rounded-lg border p-3">
        <p class="text-xs text-gray-500 uppercase">Total Oportunidades</p>
        <p class="text-xl font-bold text-gray-800">{{ $totalOps }}</p>
    </div>
    <div class="bg-white rounded-lg border p-3">
        <p class="text-xs text-gray-500 uppercase">Valor Total</p>
        <p class="text-xl font-bold text-green-600">R$ {{ number_format($totalValor, 2, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-lg border p-3">
        <p class="text-xs text-gray-500 uppercase">Ganhas</p>
        <p class="text-xl font-bold text-blue-600">{{ $ganhas }}</p>
    </div>
    <div class="bg-white rounded-lg border p-3">
        <p class="text-xs text-gray-500 uppercase">Abertas</p>
        <p class="text-xl font-bold text-yellow-600">{{ $abertas }}</p>
    </div>
</div>

{{-- Kanban board with etapas as columns --}}
<div x-data="kanbanBoard()" class="flex gap-4 overflow-x-auto pb-4" style="min-height: 70vh;">
    @foreach($funil->etapas as $etapa)
    <div class="min-w-[300px] max-w-[300px] bg-gray-50 rounded-xl flex flex-col"
         @dragover.prevent="onDragOver($event)"
         @drop="onDrop($event, {{ $etapa->id }})">
        <div class="p-3 rounded-t-xl font-semibold text-sm text-white flex items-center justify-between" style="background-color: {{ $etapa->cor ?? '#6366f1' }}">
            <span>{{ $etapa->nome }}</span>
            <span class="ml-1 bg-white/20 px-1.5 rounded text-xs">{{ $etapa->oportunidades->count() }}</span>
        </div>
        <div class="flex-1 p-2 space-y-2 overflow-y-auto">
            @foreach($etapa->oportunidades as $op)
            <div class="bg-white rounded-lg border p-3 shadow-sm hover:shadow-md transition cursor-pointer"
                 draggable="true"
                 @dragstart="onDragStart($event, {{ $op->id }})"
                 @dragend="onDragEnd($event)">
                <div class="flex items-start justify-between mb-1">
                    <h3 class="text-sm font-medium text-gray-800">{{ $op->titulo ?: $op->interessado->nome }}</h3>
                    <span class="text-xs px-1.5 py-0.5 rounded {{ $op->situacao == 'ganha' ? 'bg-green-100 text-green-700' : ($op->situacao == 'perdida' ? 'bg-red-100 text-red-700' : ($op->situacao == 'pausada' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700')) }}">
                        {{ ucfirst($op->situacao) }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mb-2"><i class="fa-solid fa-user mr-1"></i>{{ $op->interessado->nome }}</p>
                @if($op->valor)
                <p class="text-sm font-semibold text-green-600">R$ {{ number_format($op->valor, 2, ',', '.') }}</p>
                @endif
                @if($op->consultor)
                <p class="text-xs text-gray-400 mt-1"><i class="fa-solid fa-headset mr-1"></i>{{ $op->consultor->nome }}</p>
                @endif
                @if($op->data_previsao_fechamento)
                <p class="text-xs text-gray-400 mt-1"><i class="fa-regular fa-calendar mr-1"></i>{{ $op->data_previsao_fechamento->format('d/m/Y') }}</p>
                @endif
            </div>
            @endforeach

            @if($etapa->oportunidades->isEmpty())
            <div class="text-center text-gray-400 text-xs py-6">
                <i class="fa-solid fa-inbox text-2xl mb-2"></i>
                <p>Nenhuma oportunidade</p>
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>

@push('scripts')
<script>
function kanbanBoard() {
    return {
        draggedId: null,
        onDragStart(event, opId) {
            this.draggedId = opId;
            event.target.classList.add('opacity-50');
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', opId);
        },
        onDragEnd(event) {
            event.target.classList.remove('opacity-50');
            this.draggedId = null;
        },
        onDragOver(event) {
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';
        },
        onDrop(event, etapaId) {
            event.preventDefault();
            const opId = event.dataTransfer.getData('text/plain');
            if (opId) {
                fetch(`/crm/oportunidades/${opId}/mover-etapa`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ etapa_funil_id: etapaId })
                }).then(response => {
                    if (response.ok) {
                        window.location.reload();
                    }
                }).catch(() => {
                    window.location.reload();
                });
            }
        }
    };
}
</script>
@endpush
@endsection
