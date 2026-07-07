@extends('layouts.app')
@section('title', 'Configuração do CRM')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center gap-3 px-6 py-4 border-b">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">166</span>
            <h2 class="text-base font-semibold text-gray-800">Configuração do CRM</h2>
        </div>
        <form method="POST" action="{{ route('crm.configuracoes.update') }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Distribuição e Automação</h3>
                <div class="space-y-4">
                    <label class="flex items-start gap-3">
                        <input type="checkbox" name="roleta_ativa" value="1" {{ old('roleta_ativa', $config->roleta_ativa) ? 'checked' : '' }} class="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span>
                            <span class="block text-sm font-medium text-gray-700">Roleta de distribuição automática</span>
                            <span class="block text-xs text-gray-400">Novas oportunidades sem responsável são distribuídas pela proporção abaixo, priorizando os operadores do topo da lista.</span>
                        </span>
                    </label>

                    {{-- Roleta: operadores participantes com proporção (EDUQ: A recebe 3, B recebe 2, C recebe 1 por rodada) --}}
                    <div class="border rounded-lg p-4 bg-gray-50" x-data="roletaForm(@js($roleta->map(fn ($r) => ['user_id' => $r->user_id, 'proporcao' => $r->proporcao])->values()))">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-semibold text-gray-700">Operadores da roleta</p>
                            <button type="button" @click="add()" class="px-3 py-1 bg-primary-600 text-white rounded-lg text-xs font-medium hover:bg-primary-700"><i class="fa-solid fa-plus mr-1"></i> Operador</button>
                        </div>
                        <template x-for="(r, i) in linhas" :key="i">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xs text-gray-400 w-4" x-text="(i + 1) + 'º'"></span>
                                <select :name="`roleta[${i}][user_id]`" x-model="r.user_id" class="flex-1 border rounded-lg px-2 py-1.5 text-sm">
                                    <option value="">Operador...</option>
                                    @foreach($operadores as $op)<option value="{{ $op->id }}">{{ $op->nome }}</option>@endforeach
                                </select>
                                <input type="number" min="1" max="99" :name="`roleta[${i}][proporcao]`" x-model="r.proporcao" title="Quantos leads recebe por rodada" class="w-20 border rounded-lg px-2 py-1.5 text-sm text-center">
                                <span class="text-xs text-gray-400">leads/rodada</span>
                                <button type="button" @click="linhas.splice(i,1)" class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash text-xs"></i></button>
                            </div>
                        </template>
                        <p x-show="linhas.length === 0" class="text-xs text-gray-400 text-center py-2">Nenhum operador na roleta.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tempo máximo sem movimentação (minutos)</label>
                            <input type="number" min="1" name="minutos_estagnacao" value="{{ old('minutos_estagnacao', $config->minutos_estagnacao ?? 20) }}" class="w-40 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-400 mt-1">Lead abandonado além deste tempo é redistribuído para o consultor seguinte e volta ao primeiro contato.</p>
                        </div>
                        <label class="flex items-start gap-3 pt-6">
                            <input type="checkbox" name="considerar_dias_uteis" value="1" {{ old('considerar_dias_uteis', $config->considerar_dias_uteis ?? true) ? 'checked' : '' }} class="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Considerar apenas dias úteis <span class="block text-xs text-gray-400">Evita falsos alertas em sábados, domingos e feriados.</span></span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dias para perda automática</label>
                        <input type="number" min="0" name="dias_perda_automatica" value="{{ old('dias_perda_automatica', $config->dias_perda_automatica) }}" placeholder="Ex.: 30" class="w-40 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-400 mt-1">Oportunidades sem atividade por este período são marcadas como perdidas. Deixe vazio para desativar.</p>
                    </div>
                </div>
            </div>

            <div class="border-t pt-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Integração RD Station</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Token de API</label>
                        <input type="text" name="rd_station_token" value="{{ old('rd_station_token', $config->rd_station_token) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">URL do Webhook</label>
                        <input type="text" name="rd_station_url" value="{{ old('rd_station_url', $config->rd_station_url) }}" placeholder="https://..." class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-2 border-t">
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30 mt-4">
                    <i class="fa-solid fa-check mr-1"></i>Salvar Configurações
                </button>
            </div>
        </form>

        {{-- Execução manual da automação contra estagnação --}}
        <form method="POST" action="{{ route('crm.configuracoes.redistribuir') }}" class="px-6 pb-6">
            @csrf
            <button type="submit" class="px-4 py-2 border border-cyan-300 text-cyan-700 rounded-lg text-sm font-semibold hover:bg-cyan-50" onclick="return confirm('Redistribuir agora os leads estagnados pela roleta?')">
                <i class="fa-solid fa-rotate mr-1"></i> Executar roleta agora (redistribuir leads estagnados)
            </button>
        </form>
    </div>
</div>

<script>
function roletaForm(iniciais) {
    return {
        linhas: (iniciais || []).map(r => ({ user_id: r.user_id ?? '', proporcao: r.proporcao ?? 1 })),
        add() { this.linhas.push({ user_id: '', proporcao: 1 }); },
    };
}
</script>
@endsection
