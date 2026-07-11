@extends('layouts.app')
@section('title', 'Manutenção de Oportunidades')

@section('content')
@php
    $op = $oportunidade;
    $movs = $op->historicos->where('tipo', 'movimentacao')->sortByDesc('created_at');
    $feed = $op->historicos->sortByDesc('created_at');
    $atividadesPend = $op->atividades->where('situacao', 'pendente');
@endphp
<div x-data="fichaOp()" class="max-w-[1500px]">

    {{-- Cabeçalho --}}
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('crm.funil.show', $op->funil_id) }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <div>
                <h1 class="text-lg font-bold text-gray-800"><span class="text-gray-400 font-normal">109</span> Manutenção de Oportunidades (CRM)</h1>
                <p class="text-xs text-gray-400">CRM &rsaquo; Oportunidades</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button @click="linkModal = true" class="px-3 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50" title="Gerar link de matrícula online"><i class="fa-solid fa-link mr-1 text-blue-500"></i>Link de matrícula</button>
            @if(in_array($op->situacao, ['aberta', 'pausada']))
            <a href="{{ route('academico.matriculas.wizard') }}?oportunidade={{ $op->id }}"
               onclick="return confirm('Dar como GANHO e iniciar a matrícula do aluno?')"
               class="px-3 py-2 border border-green-200 bg-green-50 rounded-lg text-sm text-green-700 hover:bg-green-100"><i class="fa-solid fa-thumbs-up mr-1"></i>Ganho</a>
            <button @click="perdaModal = true" class="px-3 py-2 border border-red-200 bg-red-50 rounded-lg text-sm text-red-700 hover:bg-red-100"><i class="fa-solid fa-thumbs-down mr-1"></i>Perda</button>
            @else
            <span class="px-3 py-2 rounded-lg text-sm font-semibold {{ $op->situacao === 'ganha' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ mb_strtoupper($op->situacao) }}</span>
            @endif
        </div>
    </div>

    <div class="flex flex-col xl:flex-row gap-4 items-start">

        {{-- ============ PAINEL ESQUERDO: abas de organização e dados ============ --}}
        <div class="flex-1 w-full bg-white rounded-xl border shadow-sm">

            {{-- Cartão do lead --}}
            <div class="px-5 pt-4 pb-3 border-b">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 font-bold flex items-center justify-center">{{ mb_substr($op->interessado->nome ?? '?', 0, 1) }}</span>
                        <div>
                            <p class="font-bold text-gray-800">{{ $op->id }} {{ $op->interessado->nome ?? $op->titulo }}</p>
                            <p class="text-xs text-gray-400">
                                Criado em {{ $op->created_at->format('d/m/Y H:i') }} ·
                                Última movimentação {{ ($op->historicos->max('created_at') ?? $op->updated_at)->format('d/m/Y H:i') }}
                            </p>
                            @if($op->interessado?->celular || $op->interessado?->telefone)
                            <p class="text-xs text-gray-500 mt-0.5"><i class="fa-solid fa-phone mr-1"></i>{{ $op->interessado->celular ?? $op->interessado->telefone }}</p>
                            @endif
                        </div>
                    </div>
                    @if($op->interessado)
                    <a href="{{ route('crm.interessados.edit', $op->interessado_id) }}" class="text-xs text-blue-500 hover:underline"><i class="fa-solid fa-user mr-1"></i>Ver interessado</a>
                    @endif
                </div>
            </div>

            {{-- Abas --}}
            <div class="flex overflow-x-auto border-b text-sm">
                @foreach(['Dados Básicos', 'Atividades (' . $atividadesPend->count() . ')', 'Interesses (' . $op->interesses->count() . ')', 'Propostas', 'Possíveis matrículas', 'Histórico da movimentação'] as $i => $tab)
                @php $chave = ['dados', 'atividades', 'interesses', 'propostas', 'matriculas', 'movimentacao'][$i]; @endphp
                <button @click="aba = '{{ $chave }}'"
                        :class="aba === '{{ $chave }}' ? 'border-blue-500 text-blue-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700'"
                        class="px-4 py-2.5 border-b-2 whitespace-nowrap">{{ $tab }}</button>
                @endforeach
            </div>

            {{-- ABA: Dados Básicos --}}
            <div x-show="aba === 'dados'" class="p-5">
                <form method="POST" action="{{ route('crm.oportunidades.update', $op) }}" class="space-y-4">
                    @csrf @method('PUT')
                    <input type="hidden" name="interessado_id" value="{{ $op->interessado_id }}">
                    <input type="hidden" name="funil_id" value="{{ $op->funil_id }}">
                    <input type="hidden" name="situacao" value="{{ $op->situacao }}">
                    <input type="hidden" name="valor" value="{{ $op->valor }}">
                    <input type="hidden" name="estrelas" :value="estrelas">

                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Qualificação</label>
                        <div class="flex items-center gap-1">
                            <template x-for="i in 5">
                                <i class="fa-solid fa-star text-xl cursor-pointer"
                                   :class="i <= estrelas ? 'text-amber-400' : 'text-gray-300'"
                                   @click="estrelas = (estrelas === i ? 0 : i)"></i>
                            </template>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Origem (De onde veio) <span class="text-red-500">*</span></label>
                            <select name="origem_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                                <option value="">Selecione...</option>
                                @foreach($origens as $o)<option value="{{ $o->id }}" @selected($op->origem_id == $o->id)>{{ $o->nome }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Responsável <span class="text-red-500">*</span></label>
                            <select name="consultor_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                                <option value="">Selecione...</option>
                                @foreach($consultores as $c)<option value="{{ $c->id }}" @selected($op->consultor_id == $c->id)>{{ $c->nome }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Etapa <span class="text-red-500">*</span></label>
                            <select name="etapa_funil_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                                @foreach($etapas->where('funil_id', $op->funil_id) as $e)<option value="{{ $e->id }}" @selected($op->etapa_funil_id == $e->id)>{{ mb_strtoupper($e->nome) }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Quem indicou?</label>
                            <select name="indicacao_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                                <option value="">Selecione...</option>
                                @foreach($indicacoes as $i)<option value="{{ $i->id }}" @selected($op->indicacao_id == $i->id)>{{ $i->nome_indicado ?? ('Indicação #' . $i->id) }}</option>@endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-500 mb-1">Produtos e Serviços</label>
                            <select name="curso_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                                <option value="">Selecione o curso de interesse...</option>
                                @foreach($cursos as $c)<option value="{{ $c->id }}" @selected($op->curso_id == $c->id)>{{ $c->nome }}</option>@endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Tags (Etiquetas)</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($tagsList as $tag)
                            <label class="cursor-pointer">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="peer hidden" @checked($op->tags->contains($tag->id))>
                                <span class="inline-block px-2.5 py-1 rounded text-xs font-semibold border border-green-300 text-green-800 peer-checked:bg-green-300/80 peer-checked:border-green-400">{{ $tag->nome }}</span>
                            </label>
                            @endforeach
                            @if($op->valor)<span class="inline-block px-2.5 py-1 rounded text-xs font-semibold bg-green-300/80 text-green-950">Matrícula - {{ number_format($op->valor, 2, ',', '.') }}</span>@endif
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Motivação do interesse</label>
                        <textarea name="motivacao_interesse" rows="2" maxlength="2000" class="w-full border rounded-lg px-3 py-2 text-sm">{{ $op->motivacao_interesse }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Mídia</label>
                        <input type="text" name="midia" value="{{ $op->midia }}" maxlength="255" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div x-data="{ n: {{ mb_strlen($op->observacoes ?? '') }} }">
                        <label class="block text-xs text-gray-500 mb-1">Observação</label>
                        <textarea name="observacoes" rows="3" maxlength="2000" @input="n = $event.target.value.length" class="w-full border rounded-lg px-3 py-2 text-sm">{{ $op->observacoes }}</textarea>
                        <p class="text-right text-[11px] text-gray-400"><span x-text="n"></span> / 2000</p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-semibold"><i class="fa-solid fa-check mr-1"></i>Salvar</button>
                    </div>
                </form>
            </div>

            {{-- ABA: Atividades --}}
            <div x-show="aba === 'atividades'" x-cloak class="p-5 space-y-4" id="atividades">
                <form method="POST" action="{{ route('crm.oportunidades.atividades', $op) }}" class="grid md:grid-cols-4 gap-2 bg-gray-50 border rounded-lg p-3">
                    @csrf
                    <select name="evento_crm_id" class="border rounded-lg px-2 py-2 text-sm"><option value="">Tipo...</option>@foreach($eventos as $ev)<option value="{{ $ev->id }}">{{ $ev->nome }}</option>@endforeach</select>
                    <input type="text" name="titulo" required placeholder="O que precisa ser feito? *" class="border rounded-lg px-2 py-2 text-sm">
                    <input type="datetime-local" name="data_agendamento" required class="border rounded-lg px-2 py-2 text-sm">
                    <button class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-semibold"><i class="fa-solid fa-plus mr-1"></i>Agendar</button>
                </form>
                @forelse($op->atividades->sortByDesc('data_agendamento') as $at)
                <div class="flex items-center justify-between border rounded-lg px-3 py-2 {{ $at->situacao === 'pendente' && $at->data_agendamento?->isPast() ? 'bg-orange-50 border-orange-200' : '' }}">
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ $at->titulo }} @if($at->evento)<span class="text-xs text-gray-400">({{ $at->evento->nome }})</span>@endif</p>
                        <p class="text-xs text-gray-400">{{ $at->data_agendamento?->format('d/m/Y H:i') }} · {{ $at->responsavel->nome ?? '-' }} @if($at->descricao)· {{ $at->descricao }}@endif</p>
                    </div>
                    @if($at->situacao === 'pendente')
                    <form method="POST" action="{{ route('crm.oportunidades.atividades.concluir', [$op, $at]) }}">@csrf @method('PATCH')
                        <button class="text-xs px-2 py-1 border border-green-300 text-green-700 rounded hover:bg-green-50">Concluir</button>
                    </form>
                    @else
                    <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded font-semibold">Concluída</span>
                    @endif
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-6">Nenhuma tarefa na agenda para este lead.</p>
                @endforelse
            </div>

            {{-- ABA: Interesses --}}
            <div x-show="aba === 'interesses'" x-cloak class="p-5 space-y-3">
                <p class="text-xs text-gray-400">Vários cursos podem ser trabalhados na mesma ficha do lead.</p>
                <form method="POST" action="{{ route('crm.oportunidades.interesses', $op) }}" class="flex gap-2">
                    @csrf
                    <select name="curso_id" required class="flex-1 border rounded-lg px-3 py-2 text-sm"><option value="">Adicionar curso de interesse...</option>@foreach($cursos as $c)<option value="{{ $c->id }}">{{ $c->nome }}</option>@endforeach</select>
                    <button class="px-4 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-semibold"><i class="fa-solid fa-plus"></i></button>
                </form>
                @forelse($op->interesses as $curso)
                <div class="flex items-center justify-between border rounded-lg px-3 py-2">
                    <span class="text-sm text-gray-700"><i class="fa-solid fa-graduation-cap text-gray-300 mr-2"></i>{{ $curso->nome }}</span>
                    <form method="POST" action="{{ route('crm.oportunidades.interesses.remover', [$op, $curso]) }}">@csrf @method('DELETE')
                        <button class="text-red-400 hover:text-red-600 text-sm"><i class="fa-regular fa-trash-can"></i></button>
                    </form>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-6">Nenhum curso de interesse adicionado.</p>
                @endforelse
            </div>

            {{-- ABA: Propostas --}}
            <div x-show="aba === 'propostas'" x-cloak class="p-5 space-y-3">
                <form method="POST" action="{{ route('crm.propostas.store', $op) }}" class="grid md:grid-cols-4 gap-2 bg-gray-50 border rounded-lg p-3">
                    @csrf
                    <input type="number" step="0.01" min="0" name="valor" required placeholder="Valor (R$) *" class="border rounded-lg px-2 py-2 text-sm">
                    <input type="number" step="0.01" min="0" max="100" name="desconto_percentual" placeholder="Desconto %" class="border rounded-lg px-2 py-2 text-sm">
                    <input type="date" name="validade" class="border rounded-lg px-2 py-2 text-sm" title="Validade da oferta">
                    <button class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-semibold"><i class="fa-solid fa-plus mr-1"></i>Gerar proposta</button>
                </form>
                @forelse($propostas as $p)
                <div class="flex items-center justify-between border rounded-lg px-3 py-2">
                    <div>
                        <p class="text-sm font-medium text-gray-700">R$ {{ number_format($p->valor, 2, ',', '.') }} @if($p->desconto_percentual)<span class="text-xs text-orange-500">({{ $p->desconto_percentual }}% desc.)</span>@endif</p>
                        <p class="text-xs text-gray-400">{{ $p->created_at->format('d/m/Y') }} @if($p->validade)· válida até {{ \Carbon\Carbon::parse($p->validade)->format('d/m/Y') }}@endif</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($p->aprovacao === 'pendente')<span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-700 rounded font-semibold">Aguardando alçada</span>
                        @elseif($p->aprovacao === 'reprovada')<span class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded font-semibold">Reprovada</span>
                        @else<a href="{{ route('crm.propostas.gerar', $op) }}" class="text-xs px-2 py-1 border border-blue-300 text-blue-600 rounded hover:bg-blue-50"><i class="fa-regular fa-file-pdf mr-1"></i>PDF</a>@endif
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-6">Nenhuma proposta gerada para esta negociação.</p>
                @endforelse
            </div>

            {{-- ABA: Possíveis matrículas --}}
            <div x-show="aba === 'matriculas'" x-cloak class="p-5 space-y-3">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-center justify-between">
                    <p class="text-sm text-blue-800">Ponte com a secretaria: quando a venda estiver quase fechada, inicie a pré-configuração da matrícula (turma, disciplinas e financeiro).</p>
                    <a href="{{ route('academico.matriculas.wizard') }}?oportunidade={{ $op->id }}" class="shrink-0 ml-3 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-semibold"><i class="fa-solid fa-graduation-cap mr-1"></i>Iniciar matrícula</a>
                </div>
                @forelse($matriculasInteressado as $m)
                <a href="{{ route('academico.matriculas.ficha', $m) }}" class="flex items-center justify-between border rounded-lg px-3 py-2 hover:bg-gray-50">
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ $m->numero_matricula }}</p>
                        <p class="text-xs text-gray-400">{{ $m->turmaMontada->nome ?? '-' }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded font-semibold">{{ ucfirst($m->situacao) }}</span>
                </a>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">Nenhuma matrícula vinculada a este interessado ainda.</p>
                @endforelse
            </div>

            {{-- ABA: Histórico da movimentação --}}
            <div x-show="aba === 'movimentacao'" x-cloak class="p-5">
                <table class="w-full text-sm">
                    <thead><tr class="text-left text-xs text-gray-400 border-b"><th class="py-2">DATA</th><th>OPERADOR</th><th>MOVIMENTAÇÃO</th></tr></thead>
                    <tbody class="divide-y">
                        @forelse($movs as $m)
                        <tr><td class="py-2 text-gray-500 whitespace-nowrap pr-3">{{ $m->created_at->format('d/m/Y H:i') }}</td><td class="text-gray-500 pr-3">{{ $m->user->nome ?? 'Sistema' }}</td><td class="text-gray-700">{{ $m->texto }}</td></tr>
                        @empty
                        <tr><td colspan="3" class="py-6 text-center text-gray-400">Nenhuma movimentação registrada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ============ PAINEL DIREITO: linha do tempo (Histórico) ============ --}}
        <div class="w-full xl:w-[430px] shrink-0 bg-white rounded-xl border shadow-sm flex flex-col" style="max-height: calc(100vh - 140px);">
            <div class="px-4 py-3 border-b">
                <p class="text-sm font-bold text-gray-700 mb-2"><i class="fa-solid fa-clock-rotate-left mr-1 text-gray-400"></i>Histórico</p>
                <div class="flex flex-wrap gap-x-3 gap-y-1 text-xs text-gray-500">
                    @foreach(['anexo' => 'Anexos', 'anotacao' => 'Anotação', 'atendimento' => 'Atendimento', 'atividade' => 'Atividades', 'disparo' => 'Disparo', 'movimentacao' => 'Movimentações'] as $chave => $rotulo)
                    <label class="flex items-center gap-1 cursor-pointer"><input type="checkbox" x-model="filtros.{{ $chave }}" class="rounded text-blue-500">{{ $rotulo }}</label>
                    @endforeach
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-3 space-y-3 bg-gray-50/40">
                @php $dataAnterior = null; @endphp
                @forelse($feed as $h)
                    @php
                        $rotuloData = $h->created_at->isToday() ? 'Hoje' : ($h->created_at->isYesterday() ? 'Ontem' : $h->created_at->format('d/m/Y'));
                        $iniciais = collect(explode(' ', trim($h->user->nome ?? 'Sistema')))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('');
                    @endphp
                    @if($rotuloData !== $dataAnterior)
                        <div class="text-center"><span class="inline-block text-[10px] font-semibold text-gray-500 bg-gray-200/70 rounded-full px-3 py-0.5">{{ $rotuloData }}</span></div>
                        @php $dataAnterior = $rotuloData; @endphp
                    @endif
                    <div x-show="filtros.{{ $h->tipo }}" class="flex gap-2 items-start">
                        <span class="w-7 h-7 rounded-full {{ $h->tipo === 'movimentacao' ? 'bg-gray-200 text-gray-500' : 'bg-pink-100 text-pink-600' }} text-[10px] font-bold flex items-center justify-center shrink-0" title="{{ $h->user->nome ?? 'Sistema' }}">{{ $iniciais }}</span>
                        <div class="bg-white border rounded-lg rounded-tl-none px-3 py-2 text-sm text-gray-700 w-full">
                            @if($h->texto)<p class="whitespace-pre-line">{{ $h->texto }}</p>@endif
                            @if($h->arquivo)
                            <a href="{{ asset('storage/' . $h->arquivo) }}" target="_blank" class="inline-flex items-center gap-1 text-blue-500 hover:underline text-xs mt-1"><i class="fa-solid fa-paperclip"></i>{{ basename($h->arquivo) }}</a>
                            @endif
                            <p class="text-[10px] text-gray-400 mt-1">{{ $h->created_at->format('d/m H:i') }} · {{ \App\Models\HistoricoOportunidade::TIPOS[$h->tipo] ?? $h->tipo }}</p>
                        </div>
                    </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-10">Nenhuma interação registrada ainda.</p>
                @endforelse
            </div>

            {{-- Barra de interação: anotação + anexo + agendar (doc CRM) --}}
            <form method="POST" action="{{ route('crm.oportunidades.anotar', $op) }}" enctype="multipart/form-data" class="border-t px-3 py-2 flex items-center gap-2">
                @csrf
                <label class="cursor-pointer text-gray-400 hover:text-blue-500 p-1" title="Anexar arquivo">
                    <i class="fa-solid fa-paperclip"></i>
                    <input type="file" name="arquivo" class="hidden" @change="anexo = $event.target.files[0]?.name">
                </label>
                <button type="button" @click="agendarModal = true" class="text-gray-400 hover:text-blue-500 p-1" title="Agendar compromisso"><i class="fa-regular fa-calendar-plus"></i></button>
                <div class="flex-1">
                    <input type="text" name="texto" placeholder="Anotação..." class="w-full border rounded-full px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                    <p x-show="anexo" x-cloak class="text-[10px] text-blue-500 mt-0.5 pl-2"><i class="fa-solid fa-paperclip mr-1"></i><span x-text="anexo"></span></p>
                </div>
                <button type="submit" class="w-9 h-9 rounded-full bg-blue-500 hover:bg-blue-600 text-white flex items-center justify-center shrink-0" title="Salvar"><i class="fa-solid fa-paper-plane text-sm"></i></button>
            </form>
        </div>
    </div>

    {{-- Modal: Agendar --}}
    <div x-show="agendarModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" @click.self="agendarModal = false">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-1">Agendar compromisso</h3>
            <p class="text-xs text-gray-400 mb-4">A tarefa alimenta a aba Atividades, a agenda do vendedor e a notificação no vencimento.</p>
            <form method="POST" action="{{ route('crm.oportunidades.atividades', $op) }}" class="space-y-3">
                @csrf
                <select name="evento_crm_id" class="w-full border rounded-lg px-3 py-2 text-sm"><option value="">Tipo de atividade...</option>@foreach($eventos as $ev)<option value="{{ $ev->id }}">{{ $ev->nome }}</option>@endforeach</select>
                <input type="text" name="titulo" required placeholder="O que precisa ser feito? *" class="w-full border rounded-lg px-3 py-2 text-sm">
                <input type="datetime-local" name="data_agendamento" required class="w-full border rounded-lg px-3 py-2 text-sm">
                <textarea name="descricao" rows="2" placeholder="Descrição" class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
                <div class="flex justify-end gap-2 pt-1">
                    <button type="button" @click="agendarModal = false" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-semibold">Agendar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Perda --}}
    <div x-show="perdaModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" @click.self="perdaModal = false">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Marcar como Perdida</h3>
            <form method="POST" action="{{ route('crm.oportunidades.perder', $op) }}" class="space-y-3">
                @csrf
                <select name="motivo_perda_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Motivo da perda... *</option>
                    @foreach($motivosPerda as $mp)<option value="{{ $mp->id }}">{{ $mp->nome }}</option>@endforeach
                </select>
                <div class="flex justify-end gap-2 pt-1">
                    <button type="button" @click="perdaModal = false" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg text-sm font-semibold">Confirmar Perda</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Gerar link de matrícula online (doc CRM) --}}
    <div x-show="linkModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" @click.self="linkModal = false">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-3">Gerar link de matrícula online</h3>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-xs text-yellow-800 mb-4">
                <p class="font-semibold mb-1"><i class="fa-solid fa-triangle-exclamation mr-1"></i>Aviso</p>
                Ao definir uma data de expiração, o link será bloqueado após o vencimento e o sistema criará automaticamente uma atividade de cobrança na aba Atividades deste card. Cupons de desconto do processo são aplicados automaticamente no checkout.
            </div>
            <form method="POST" action="{{ route('crm.oportunidades.gerar-link', $op) }}" class="space-y-3" x-data="{ expira: false }">
                @csrf
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Processo de Inscrição <span class="text-red-500">*</span></label>
                    <select name="abertura_matricula_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($aberturas as $ab)<option value="{{ $ab->id }}">{{ $ab->nome }}</option>@endforeach
                    </select>
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="novo_checkout" value="1" checked class="rounded text-blue-500">Gerar link no novo modelo de checkout?
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" x-model="expira" class="rounded text-blue-500">Gerar link com data de expiração?
                </label>
                <template x-if="expira">
                    <input type="datetime-local" name="expira_em" class="w-full border rounded-lg px-3 py-2 text-sm">
                </template>
                <div class="flex justify-end gap-2 pt-1">
                    <button type="button" @click="linkModal = false" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</button>
                    <button type="submit" class="px-5 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-semibold">Gerar</button>
                </div>
            </form>
            @if($op->linksMatricula->isNotEmpty())
            <div class="mt-4 border-t pt-3 space-y-1">
                <p class="text-xs font-semibold text-gray-500">Links gerados</p>
                @foreach($op->linksMatricula->sortByDesc('id') as $l)
                <div class="flex items-center justify-between text-xs">
                    <span class="truncate text-gray-500">{{ $l->abertura->nome ?? 'Processo' }} @if($l->expira_em)· expira {{ $l->expira_em->format('d/m/Y H:i') }}@endif @if($l->expirado())<span class="text-red-500 font-semibold">(expirado)</span>@endif</span>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ route('matricula-link', $l->token) }}'); this.innerText='Copiado!'" class="shrink-0 ml-2 px-2 py-1 border border-blue-300 text-blue-600 rounded hover:bg-blue-50">Copiar link</button>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- Caixa com o link recém-gerado (doc: botão Copiar Link) --}}
    @if(session('link_gerado'))
    <div x-data="{ aberto: true }" x-show="aberto" class="fixed bottom-4 left-1/2 -translate-x-1/2 z-50 bg-white border shadow-xl rounded-xl px-4 py-3 flex items-center gap-3">
        <span class="text-sm text-gray-600 max-w-[400px] truncate">{{ session('link_gerado') }}</span>
        <button onclick="navigator.clipboard.writeText('{{ session('link_gerado') }}'); this.innerText='Copiado!'" class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-xs font-semibold">Copiar Link</button>
        <button @click="aberto = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
    </div>
    @endif
</div>

@push('scripts')
<script>
function fichaOp() {
    return {
        aba: window.location.hash === '#atividades' ? 'atividades' : 'dados',
        estrelas: {{ (int) ($op->estrelas ?? 0) }},
        filtros: { anexo: true, anotacao: true, atendimento: true, atividade: true, disparo: true, movimentacao: true },
        agendarModal: false,
        perdaModal: false,
        linkModal: {{ session('link_gerado') ? 'true' : 'false' }},
        anexo: null,
    };
}
</script>
@endpush
@endsection
