{{-- Coluna do Kanban 110 (doc CRM). Vars: $nome, $icone, $ops, $dropEtapa, $dropTipo, $totalReal? --}}
<div class="min-w-[270px] w-[270px] border-r border-gray-100 flex flex-col shrink-0"
     @dragover.prevent
     @drop="onDrop($event, '{{ $dropTipo }}', {{ $dropEtapa ?? 'null' }})">

    <div class="flex items-center justify-between px-3 py-2.5 border-b border-gray-100 bg-gray-50/60">
        <span class="text-[11px] font-bold text-gray-600 tracking-wide flex items-center gap-1.5">
            @if($icone)<i class="fa-solid {{ $icone }}"></i>@endif
            {{ $nome }}
        </span>
        <span class="text-[10px] text-gray-400 bg-gray-100 rounded px-1.5 py-0.5 font-semibold">{{ $ops->count() }}/{{ $totalReal ?? $ops->count() }}</span>
    </div>

    <div class="flex-1 p-2 space-y-2 overflow-y-auto bg-gray-50/30">
        @forelse($ops as $op)
        @php
            $idade = $op->diasNoFunil();
            $estagnado = $op->diasSemInteracao();
            $atrasada = $op->atividades->contains(fn ($a) => $a->situacao === 'pendente' && $a->data_agendamento && $a->data_agendamento->isPast());
            $iniciais = collect(explode(' ', trim($op->consultor->nome ?? '')))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('');
            $buscavel = strtolower($op->id . ' ' . ($op->interessado->nome ?? '') . ' ' . ($op->interessado->celular ?? $op->interessado->telefone ?? ''));
        @endphp
        <div data-card
             x-show="combina(@js($buscavel))"
             draggable="true"
             @dragstart="onDragStart($event, {{ $op->id }})"
             @dragend="onDragEnd($event)"
             @click="abrir({{ $op->id }})"
             class="bg-white rounded-lg border shadow-sm hover:shadow-md transition cursor-pointer {{ $atrasada ? 'border-orange-200 !bg-orange-50' : 'border-gray-200' }}">

            {{-- ID + Nome + Contato rápido + agendamento rápido --}}
            <div class="px-3 pt-2.5 flex items-start justify-between gap-1">
                <p class="text-[13px] leading-snug">
                    <span class="text-gray-400">{{ $op->id }}</span>
                    <span class="font-bold text-gray-800">{{ $op->interessado->nome ?? $op->titulo }}</span>
                    @if($op->interessado?->celular || $op->interessado?->telefone)
                    <span class="font-bold text-gray-800">- {{ $op->interessado->celular ?? $op->interessado->telefone }}</span>
                    @endif
                </p>
                <a href="{{ url('crm/oportunidades/' . $op->id . '/edit') }}#atividades" @click.stop
                   class="shrink-0 p-1 rounded {{ $atrasada ? 'text-red-500 bg-red-50' : 'text-gray-400 hover:text-blue-500' }}" title="Agendar tarefa rápida">
                    <i class="fa-regular fa-calendar"></i>
                </a>
            </div>

            <div x-show="viz !== 'P'" class="px-3 pt-1 space-y-0.5">
                @if($op->interessado?->celular || $op->interessado?->telefone)
                <p class="text-xs text-gray-500"><i class="fa-solid fa-phone text-[10px] mr-1.5"></i>{{ $op->interessado->celular ?? $op->interessado->telefone }}</p>
                @endif
                @if($op->origem)
                <p class="text-xs text-gray-500"><i class="fa-regular fa-flag text-[10px] mr-1.5"></i>{{ $op->origem->nome }}</p>
                @endif
            </div>

            {{-- Tags verdes de interesse (doc: etiquetas com valor e curso alvo) --}}
            <div x-show="viz === 'G'" class="px-3 pt-1.5 flex flex-wrap gap-1">
                @if($op->valor)
                <span class="bg-green-300/80 text-green-950 text-[10px] font-semibold px-1.5 py-0.5 rounded">Matrícula - {{ number_format($op->valor, 2, ',', '.') }}</span>
                @endif
                @if($op->curso)
                <span class="bg-green-300/80 text-green-950 text-[10px] font-semibold px-1.5 py-0.5 rounded truncate max-w-full">{{ $op->curso->nome }}</span>
                @endif
                @foreach($op->tags as $tag)
                <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded text-white" style="background-color: {{ $tag->cor ?? '#22c55e' }}">{{ $tag->nome }}</span>
                @endforeach
            </div>

            {{-- Rodapé: relógio (idade), cronômetro (estagnação), estrelas, avatar --}}
            <div class="px-3 py-2 mt-1.5 border-t border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2 text-[11px]">
                    <span class="text-gray-500" title="Tempo no funil"><i class="fa-regular fa-clock mr-0.5"></i>{{ $idade }}d</span>
                    <span class="{{ $estagnado >= 5 ? 'text-red-500 font-semibold' : 'text-gray-500' }}" title="Dias sem interação"><i class="fa-solid fa-stopwatch mr-0.5"></i>{{ $estagnado }}d</span>
                </div>
                <div class="flex items-center gap-0.5">
                    @for($i = 1; $i <= 5; $i++)
                    <i data-star="{{ $op->id }}" @click="setEstrelas({{ $op->id }}, {{ $i }}, $event)"
                       class="fa-solid fa-star text-[10px] cursor-pointer {{ $i <= ($op->estrelas ?? 0) ? 'text-amber-400' : 'text-gray-300' }}" title="Qualificação {{ $i }}/5"></i>
                    @endfor
                </div>
                <span class="w-6 h-6 rounded-full bg-pink-100 text-pink-600 text-[10px] font-bold flex items-center justify-center" title="{{ $op->consultor->nome ?? 'Sem responsável' }}">{{ $iniciais ?: '—' }}</span>
            </div>
        </div>
        @empty
        <div class="text-center text-gray-400 pt-24">
            <i class="fa-solid fa-box-open text-4xl text-gray-200 mb-3"></i>
            <p class="text-xs">Nenhuma oportunidade aqui.</p>
        </div>
        @endforelse
    </div>
</div>
