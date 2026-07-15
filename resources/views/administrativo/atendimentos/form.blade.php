@extends('layouts.app')
@section('title', 'Manutenção de Atendimentos')

@section('content')
<div class="w-full"
     x-data="{
        portal: {{ old('portal_aluno', $atendimento->portal_aluno ?? false) ? 'true' : 'false' }},
        finalizado: {{ old('situacao', $atendimento->situacao ?? 'aberto') === 'concluido' ? 'true' : 'false' }},
        retorno: {{ old('precisa_retorno', $atendimento->precisa_retorno ?? false) ? 'true' : 'false' }},
        deptos: {{ old('departamentos_responsavel', $atendimento->departamentos_responsavel ?? false) ? 'true' : 'false' }},
        objetivo: {{ old('objetivo_alcancado', $atendimento->objetivo_alcancado ?? true) ? 'true' : 'false' }}
     }">
    <div class="bg-white">
        <div class="px-5 py-3 border-b flex items-center gap-3">
            <a href="{{ route('atendimentos.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <span class="text-sm font-semibold text-gray-400">55</span>
            <div>
                <h1 class="text-lg font-bold text-gray-800">Manutenção de Atendimentos</h1>
                <p class="text-xs text-primary-500">Pós Vendas › Atendimentos</p>
            </div>
        </div>
        <div class="px-5 pt-3 border-b">
            <span class="inline-block pb-2 text-sm font-semibold text-cyan-600 border-b-2 border-cyan-500">Dados Básicos</span>
        </div>
        <form method="POST" action="{{ isset($atendimento) ? route('atendimentos.update', $atendimento) : route('atendimentos.store') }}" class="p-5 space-y-4">
            @csrf
            @if(isset($atendimento)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-sm font-medium text-gray-700">Pessoa <span class="text-red-500">*</span></label>
                    <x-pessoa-quick-add target="pessoa_id" />
                </div>
                <select name="pessoa_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
                    <option value="">Selecione...</option>
                    @foreach($pessoas as $p)
                    <option value="{{ $p->id }}" @selected(old('pessoa_id', $atendimento->pessoa_id ?? '') == $p->id)>{{ $p->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Categoria de Atendimento</label>
                <select name="categoria_atendimento_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Selecione...</option>
                    @foreach($categorias as $c)
                    <option value="{{ $c->id }}" @selected(old('categoria_atendimento_id', $atendimento->categoria_atendimento_id ?? '') == $c->id)>{{ $c->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Responsável pelo Contato</label>
                <select name="responsavel_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Selecione...</option>
                    @foreach($responsaveis as $r)
                    <option value="{{ $r->id }}" @selected(old('responsavel_id', $atendimento->responsavel_id ?? '') == $r->id)>{{ $r->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Canal de atendimento</label>
                <select name="canal" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Selecione...</option>
                    @foreach(['Presencial', 'Telefone', 'WhatsApp', 'E-mail', 'Portal do Aluno', 'Redes Sociais'] as $canal)
                    <option value="{{ $canal }}" @selected(old('canal', $atendimento->canal ?? '') == $canal)>{{ $canal }}</option>
                    @endforeach
                </select>
            </div>

            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="portal_aluno" :value="portal ? 1 : 0">
                <button type="button" @click="portal = !portal" :class="portal ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                    <span :class="portal ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                </button>
                <span class="text-sm font-medium text-gray-700">Apresentar atendimento no portal do aluno?</span>
            </label>

            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="situacao" :value="finalizado ? 'concluido' : 'aberto'">
                <button type="button" @click="finalizado = !finalizado" :class="finalizado ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                    <span :class="finalizado ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                </button>
                <span class="text-sm font-medium text-gray-700">Atendimento foi finalizado?</span>
            </label>

            <div x-show="finalizado" x-cloak class="ml-6 pl-4 border-l-2 border-cyan-200 space-y-3">
                <p class="text-xs text-amber-600"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Um atendimento finalizado nunca poderá ser reaberto. Nova dúvida exigirá um novo protocolo.</p>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="objetivo_alcancado" :value="objetivo ? 1 : 0">
                    <button type="button" @click="objetivo = !objetivo" :class="objetivo ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                        <span :class="objetivo ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                    </button>
                    <span class="text-sm font-medium text-gray-700">O objetivo do atendimento foi alcançado com sucesso?</span>
                </label>
                <div x-show="!objetivo" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Motivo <span class="text-red-500">*</span> <span class="text-xs text-gray-400">(alimenta os relatórios de auditoria e o painel de eficiência)</span></label>
                    <select name="motivo_falha_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <option value="">Selecione...</option>
                        @foreach($motivosFalha ?? [] as $mf)
                        <option value="{{ $mf->id }}" @selected(old('motivo_falha_id', $atendimento->motivo_falha_id ?? '') == $mf->id)>{{ $mf->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="precisa_retorno" :value="retorno ? 1 : 0">
                <button type="button" @click="retorno = !retorno" :class="retorno ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                    <span :class="retorno ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                </button>
                <span class="text-sm font-medium text-gray-700">Será preciso dar algum retorno para este atendimento?</span>
            </label>

            <div x-show="retorno" x-cloak class="ml-6 pl-4 border-l-2 border-cyan-200">
                <label class="block text-sm font-medium text-gray-700 mb-1">Data do retorno <span class="text-xs text-gray-400">(gera atividade pendente no painel do operador)</span></label>
                <input type="date" name="data_retorno" value="{{ old('data_retorno', isset($atendimento) && $atendimento->data_retorno ? $atendimento->data_retorno->format('Y-m-d') : '') }}" class="w-full md:w-60 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>

            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="departamentos_responsavel" :value="deptos ? 1 : 0">
                <button type="button" @click="deptos = !deptos" :class="deptos ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                    <span :class="deptos ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                </button>
                <span class="text-sm font-medium text-gray-700">Adicionar departamentos como responsável?</span>
            </label>

            <div class="border-t pt-4">
                <h3 class="text-sm font-bold text-gray-700 mb-3">Histórico</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                    <textarea name="descricao" rows="4" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>{{ old('descricao', $atendimento->descricao ?? '') }}</textarea>
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Resolução</label>
                    <textarea name="resolucao" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">{{ old('resolucao', $atendimento->resolucao ?? '') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end pt-3 sticky bottom-4 z-10">
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30"><i class="fa-solid fa-check mr-1"></i>Salvar</button>
            </div>
        </form>

        {{-- Chat do protocolo (EDUQ 55): interações sem encerrar; internas ficam ocultas ao aluno --}}
        @if(isset($atendimento))
        <div class="border-t px-5 py-4">
            <h3 class="text-sm font-bold text-gray-700 mb-3">Interações do protocolo <span class="text-xs font-normal text-gray-400">({{ $atendimento->interacoes->count() }})</span></h3>
            <div class="space-y-2 mb-3 max-h-80 overflow-y-auto">
                @forelse($atendimento->interacoes as $msg)
                <div class="rounded-lg px-3 py-2 text-sm {{ $msg->interna ? 'bg-yellow-50 border border-yellow-200' : 'bg-gray-50 border' }}">
                    <div class="flex items-center gap-2 text-xs text-gray-400 mb-0.5">
                        <span class="font-semibold text-gray-600">{{ $msg->user?->nome ?? 'Sistema' }}</span>
                        <span>{{ $msg->created_at->format('d/m/Y H:i') }}</span>
                        @if($msg->interna)<span class="text-yellow-700 font-semibold"><i class="fa-solid fa-eye-slash mr-0.5"></i>Interna (oculta ao aluno)</span>@endif
                    </div>
                    <p class="text-gray-700 whitespace-pre-line">{{ $msg->mensagem }}</p>
                </div>
                @empty
                <p class="text-xs text-gray-400 text-center py-2">Nenhuma interação registrada.</p>
                @endforelse
            </div>
            @if(!in_array($atendimento->situacao, ['concluido', 'falha']))
            <form method="POST" action="{{ route('atendimentos.interagir', $atendimento) }}" class="space-y-2">
                @csrf
                <textarea name="mensagem" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" placeholder="Escreva a interação... (responder não encerra o protocolo)" required></textarea>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-xs text-gray-600">
                        <input type="checkbox" name="interna" value="1" class="rounded border-gray-300 text-yellow-500">
                        Mensagem interna (não aparece para o aluno no portal)
                    </label>
                    <button class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-paper-plane mr-1"></i>Enviar</button>
                </div>
            </form>
            @else
            <p class="text-xs text-red-500">Atendimento finalizado não recebe novas interações — nova dúvida exige novo protocolo.</p>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
