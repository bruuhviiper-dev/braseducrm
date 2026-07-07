@extends('layouts.app')
@section('title', 'Cálculo de Comissões')

@section('content')
<div class="w-full" x-data>
    <div class="bg-white">
        <div class="px-2 pt-1 pb-3 flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-start gap-2">
                <span class="text-base font-semibold text-gray-400 mt-0.5">222</span>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Cálculo de Comissões</h1>
                    <p class="text-xs text-gray-400">Financeiro › Comissões</p>
                </div>
            </div>
        </div>

        {{-- Filtros --}}
        <form method="GET" class="flex flex-wrap items-end gap-3 mb-4 px-2">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Consultor</label>
                <select name="consultor_id" class="border rounded-lg px-3 py-2 text-sm w-56">
                    <option value="">Todos</option>
                    @foreach($consultores as $c)
                    <option value="{{ $c->id }}" @selected(request('consultor_id') == $c->id)>{{ $c->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Matrículas de</label>
                <input type="date" name="de" value="{{ request('de') }}" class="border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">até</label>
                <input type="date" name="ate" value="{{ request('ate') }}" class="border rounded-lg px-3 py-2 text-sm">
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-100 border rounded-lg text-sm text-gray-600 hover:bg-gray-200"><i class="fa-solid fa-filter mr-1"></i> Filtrar</button>
        </form>

        {{-- Fechamento --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4 px-2">
            <div class="border-t-4 border-primary-400 border rounded-lg px-3 py-3 bg-gray-50">
                <p class="text-[11px] uppercase tracking-wide text-gray-400">Vendas no período</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $matriculas->total() }}</p>
            </div>
            <div class="border-t-4 border-cyan-400 border rounded-lg px-3 py-3 bg-gray-50">
                <p class="text-[11px] uppercase tracking-wide text-gray-400">Base de cálculo</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">R$ {{ number_format($totalBase, 2, ',', '.') }}</p>
            </div>
            <div class="border-t-4 border-green-400 border rounded-lg px-3 py-3 bg-gray-50 col-span-2">
                <p class="text-[11px] uppercase tracking-wide text-gray-400">Total de comissões (fechamento)</p>
                <p class="text-2xl font-bold text-green-600 mt-1">R$ {{ number_format($totalComissao, 2, ',', '.') }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('financeiro.comissoes.salvar') }}">
            @csrf
            <input type="hidden" name="consultor_id" value="{{ request('consultor_id') }}">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Matrícula</th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Aluno</th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Curso</th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">Base (R$)</th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase w-56">Vendedor</th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase w-28">% Comissão</th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right w-36">Comissão (R$)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($matriculas as $m)
                        @php
                            $base = (float) ($m->valor_total ?? 0);
                            $percPadrao = (float) ($m->turma?->curso?->valor_comissao ?? 0);
                            $perc = $m->comissao_percentual !== null ? (float) $m->comissao_percentual : $percPadrao;
                        @endphp
                        <tr class="hover:bg-gray-50" x-data="{ base: {{ $base }}, perc: {{ $perc }} }">
                            <td class="px-4 py-2.5 font-semibold text-gray-800">{{ $m->numero_matricula ?: '#' . $m->id }}<p class="text-[11px] text-gray-400 font-normal">{{ $m->data_matricula?->format('d/m/Y') }}</p></td>
                            <td class="px-4 py-2.5 text-gray-700">{{ $m->aluno?->pessoa?->nome ?? '—' }}</td>
                            <td class="px-4 py-2.5 text-gray-600">{{ $m->turma?->curso?->nome ?? '—' }}</td>
                            <td class="px-4 py-2.5 text-right text-gray-700">{{ number_format($base, 2, ',', '.') }}</td>
                            <td class="px-4 py-2.5">
                                <select name="linhas[{{ $m->id }}][consultor_id]" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                                    <option value="">— sem vendedor —</option>
                                    @foreach($consultores as $c)
                                    <option value="{{ $c->id }}" @selected($m->consultor_id == $c->id)>{{ $c->nome }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-4 py-2.5">
                                <div class="relative">
                                    <input type="number" step="0.01" min="0" max="100" name="linhas[{{ $m->id }}][percentual]"
                                           value="{{ $m->comissao_percentual !== null ? $m->comissao_percentual : '' }}"
                                           placeholder="{{ number_format($percPadrao, 2, '.', '') }}"
                                           x-on:input="perc = parseFloat($event.target.value || $event.target.placeholder) || 0"
                                           class="w-full border rounded-lg px-2 py-1.5 text-sm text-right pr-6">
                                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">%</span>
                                </div>
                            </td>
                            <td class="px-4 py-2.5 text-right font-semibold text-green-600" x-text="'R$ ' + (base * perc / 100).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Nenhuma matrícula no período/filtro selecionado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <p class="text-xs text-gray-400 mt-3 px-2"><i class="fa-solid fa-circle-info mr-1"></i> O percentual em branco usa a comissão padrão do curso. Edite livremente linha a linha (ex.: 20% padrão → 10% para o workshop) — o valor líquido é recalculado na hora e espelhado no fechamento.</p>

            <div class="mt-4 px-2">{{ $matriculas->links() }}</div>

            <div class="flex justify-end pt-3 sticky bottom-4 z-10">
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30"><i class="fa-solid fa-check mr-1"></i>Salvar Comissões</button>
            </div>
        </form>
    </div>
</div>
@endsection
