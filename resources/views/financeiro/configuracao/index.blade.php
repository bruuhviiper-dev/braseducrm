@extends('layouts.app')
@section('title', 'Configuração do Financeiro')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center gap-3 px-6 py-4 border-b">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">59</span>
            <h2 class="text-base font-semibold text-gray-800">Configuração do Financeiro</h2>
        </div>
        <form method="POST" action="{{ route('financeiro.configuracao.index') }}" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Multa por atraso (%)</label>
                    <input type="number" step="0.01" min="0" name="multa_atraso" value="{{ old('multa_atraso', $config->multa_atraso) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Juros ao dia (%)</label>
                    <input type="number" step="0.0001" min="0" name="juros_dia" value="{{ old('juros_dia', $config->juros_dia) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <label class="flex items-start gap-3">
                <input type="checkbox" name="boleto_automatico" value="1" {{ old('boleto_automatico', $config->boleto_automatico) ? 'checked' : '' }} class="mt-0.5 rounded border-gray-300 text-blue-600">
                <span><span class="block text-sm font-medium text-gray-700">Boleto automático</span><span class="block text-xs text-gray-400">Gera boletos automaticamente para os títulos a receber.</span></span>
            </label>
            <label class="flex items-start gap-3">
                <input type="checkbox" name="cartao_recorrente" value="1" {{ old('cartao_recorrente', $config->cartao_recorrente) ? 'checked' : '' }} class="mt-0.5 rounded border-gray-300 text-blue-600">
                <span><span class="block text-sm font-medium text-gray-700">Cartão recorrente</span><span class="block text-xs text-gray-400">Cobrança recorrente no cartão de crédito.</span></span>
            </label>

            {{-- Régua de cobrança dos docs do EDUQ: avisos antes/depois do vencimento e confirmação de baixa --}}
            <div class="border-t pt-4" x-data="reguasForm()">
                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Régua de Cobrança</h3>
                    <button type="button" @click="add()" class="px-3 py-1 bg-primary-600 text-white rounded-lg text-xs font-medium hover:bg-primary-700"><i class="fa-solid fa-plus mr-1"></i> Regra</button>
                </div>
                <p class="text-xs text-gray-400 mb-3">Variáveis da mensagem: <code>{nome}</code>, <code>{valor}</code>, <code>{vencimento}</code>, <code>{documento}</code>.</p>
                <template x-for="(r, i) in linhas" :key="i">
                    <div class="border rounded-lg p-3 mb-2 bg-gray-50 space-y-2">
                        <div class="flex items-center gap-2">
                            <select :name="`reguas[${i}][tipo]`" x-model="r.tipo" class="flex-1 border rounded-lg px-2 py-1.5 text-sm">
                                <option value="antecedencia">Aviso de vencimento (dias ANTES)</option>
                                <option value="atraso">Cobrança por parcela (dias APÓS o vencimento)</option>
                                <option value="pagamento">Aviso de pagamento (confirmação da baixa)</option>
                            </select>
                            <input type="number" min="0" max="365" :name="`reguas[${i}][dias]`" x-model="r.dias" x-show="r.tipo !== 'pagamento'" title="Dias" class="w-20 border rounded-lg px-2 py-1.5 text-sm text-center" placeholder="dias">
                            <select :name="`reguas[${i}][canal]`" x-model="r.canal" class="w-32 border rounded-lg px-2 py-1.5 text-sm">
                                <option value="email">E-mail</option>
                                <option value="sms">SMS</option>
                                <option value="whatsapp">WhatsApp</option>
                            </select>
                            <button type="button" @click="linhas.splice(i,1)" class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash text-xs"></i></button>
                        </div>
                        <textarea :name="`reguas[${i}][mensagem]`" x-model="r.mensagem" rows="2" class="w-full border rounded-lg px-2 py-1.5 text-sm" placeholder="Olá {nome}, a parcela {documento} de {valor} vence em {vencimento}." required></textarea>
                        <label class="flex items-center gap-2 text-xs text-gray-600">
                            <input type="hidden" :name="`reguas[${i}][filtrar_ja_notificados]`" :value="r.filtrar_ja_notificados ? 1 : 0">
                            <input type="checkbox" x-model="r.filtrar_ja_notificados" class="rounded border-gray-300 text-blue-600">
                            Filtrar já notificados (não repete a mensagem para o mesmo título nesta regra)
                        </label>
                    </div>
                </template>
                <p x-show="linhas.length === 0" class="text-xs text-gray-400 text-center py-2">Nenhuma regra de cobrança cadastrada.</p>
            </div>

            <div class="border-t pt-4">
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30">Salvar Configuração</button>
            </div>
        </form>

        {{-- Execução manual da régua (no EDUQ roda agendada; aqui pode ser disparada na hora) --}}
        <form method="POST" action="{{ route('financeiro.configuracao.processar-reguas') }}" class="px-6 pb-6">
            @csrf
            <button type="submit" class="px-4 py-2 border border-cyan-300 text-cyan-700 rounded-lg text-sm font-semibold hover:bg-cyan-50" onclick="return confirm('Processar agora a régua de cobrança (avisos de vencimento, atraso e baixa)?')">
                <i class="fa-solid fa-paper-plane mr-1"></i> Processar régua de cobrança agora
            </button>
        </form>
    </div>
</div>

<script>
function reguasForm() {
    return {
        linhas: ({!! $reguas->map(fn ($r) => ['tipo' => $r->tipo, 'dias' => $r->dias, 'canal' => $r->canal, 'mensagem' => $r->mensagem, 'filtrar_ja_notificados' => (bool) $r->filtrar_ja_notificados])->values()->toJson() !!} || []),
        add() { this.linhas.push({ tipo: 'antecedencia', dias: 3, canal: 'email', mensagem: '', filtrar_ja_notificados: true }); },
    };
}
</script>
@endsection
