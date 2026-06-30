@extends('layouts.app')
@section('title', 'Preenchimento Plano de Ensino')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">119</span>
                <div>
                    <h1 class="text-base font-semibold text-gray-800">{{ $turma_montada->nome ?? 'Turma #'.$turma_montada->id }}</h1>
                    <p class="text-xs text-gray-500">{{ $disciplina->nome }}</p>
                </div>
            </div>
            <a href="{{ route('academico.planos-ensino.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>

        <div class="p-6 space-y-5">
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-2 rounded text-sm">{{ session('success') }}</div>
            @endif

            {{-- Selecao da estrutura (recarrega os topicos) --}}
            <form method="GET" action="{{ route('academico.planos-ensino.preencher', [$turma_montada->id, $disciplina->id]) }}">
                <label class="block text-sm font-medium text-gray-700 mb-1">Estrutura do Plano <span class="text-red-500">*</span></label>
                <div class="flex gap-2">
                    <select name="estrutura" onchange="this.form.submit()" class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">Selecione a estrutura...</option>
                        @foreach($estruturas as $e)
                        <option value="{{ $e->id }}" {{ optional($estrutura)->id === $e->id ? 'selected' : '' }}>{{ $e->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            @if($estrutura)
                @if($estrutura->topicos->isEmpty())
                <p class="text-sm text-gray-400">Esta estrutura não tem tópicos. Edite a estrutura e adicione tópicos.</p>
                @else
                <form method="POST" action="{{ route('academico.planos-ensino.salvar', [$turma_montada->id, $disciplina->id]) }}" class="space-y-4">
                    @csrf @method('PUT')
                    <input type="hidden" name="estrutura_plano_id" value="{{ $estrutura->id }}">

                    @foreach($estrutura->topicos as $topico)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ $topico->nome }}
                            @if($topico->obrigatoria)<span class="text-red-500">*</span>@endif
                        </label>
                        <textarea name="conteudo[{{ $topico->id }}]" rows="3" {{ $topico->obrigatoria ? 'required' : '' }}
                                  class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">{{ $conteudos[$topico->id] ?? '' }}</textarea>
                    </div>
                    @endforeach

                    <div class="flex justify-end gap-3 pt-2 border-t">
                        <a href="{{ route('academico.planos-ensino.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar Plano</button>
                    </div>
                </form>
                @endif
            @else
            <p class="text-sm text-gray-400">Selecione uma estrutura para preencher o plano de ensino.</p>
            @endif
        </div>
    </div>
</div>
@endsection
