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
                <label class="block text-sm font-medium text-gray-700 mb-1">Estrutura de Plano de Ensino</label>
                <select name="estrutura" onchange="this.form.submit()" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Selecione a estrutura...</option>
                    @foreach($estruturas as $e)
                    <option value="{{ $e->id }}" {{ optional($estrutura)->id === $e->id ? 'selected' : '' }}>{{ $e->nome }}</option>
                    @endforeach
                </select>
            </form>

            {{-- Formulário principal (toggle + tópicos + salvar) --}}
            <form method="POST" action="{{ route('academico.planos-ensino.salvar', [$turma_montada->id, $disciplina->id]) }}" class="space-y-5" enctype="multipart/form-data">
                @csrf @method('PUT')
                @if($estrutura)<input type="hidden" name="estrutura_plano_id" value="{{ $estrutura->id }}">@endif

                {{-- Ocultar no portal do aluno (fiel ao EDUQ) --}}
                <label class="flex items-center gap-3 text-sm">
                    <input type="checkbox" name="ocultar_portal" value="1" {{ old('ocultar_portal', $plano->ocultar_portal ?? false) ? 'checked' : '' }} class="rounded text-primary-600 w-5 h-5">
                    <span class="text-gray-700">Ocultar plano de ensino no portal do aluno?</span>
                </label>

                {{-- Tópicos da estrutura --}}
                @if($estrutura)
                    @if($estrutura->topicos->isEmpty())
                    <p class="text-sm text-gray-400">Esta estrutura não tem tópicos. Edite a estrutura e adicione tópicos.</p>
                    @else
                    <div class="space-y-4 border-t pt-4">
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
                    </div>
                    @endif
                @endif

                {{-- Anexos (fiel ao EDUQ) --}}
                <div class="border rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-600 mb-2"><i class="fa-solid fa-paperclip mr-1"></i> Anexos</p>
                    <div class="flex items-center gap-2">
                        <input type="file" name="anexo" class="text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-primary-50 file:text-primary-600 file:text-sm hover:file:bg-primary-100">
                    </div>
                    @if(!empty($plano->anexo_path))
                    <p class="text-xs mt-2"><a href="{{ asset('storage/'.$plano->anexo_path) }}" target="_blank" class="text-primary-600 hover:underline"><i class="fa-solid fa-file mr-1"></i>{{ basename($plano->anexo_path) }}</a></p>
                    @endif
                    <p class="text-xs text-gray-400 mt-2">Anexe materiais de apoio ao plano de ensino.</p>
                </div>

                <div class="flex justify-end gap-3 pt-2 border-t">
                    <a href="{{ route('academico.planos-ensino.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
