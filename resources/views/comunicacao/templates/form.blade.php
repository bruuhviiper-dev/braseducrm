@extends('layouts.app')
@section('title', isset($template) ? 'Editar Template' : 'Novo Template')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl border p-6">
        <h1 class="text-lg font-semibold text-gray-800 mb-6">{{ isset($template) ? 'Editar Template' : 'Novo Template de Mensagem' }}</h1>

        <form method="POST" action="{{ isset($template) ? route('comunicacao.templates.update', $template) : route('comunicacao.templates.store') }}">
            @csrf
            @if(isset($template)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                    <input type="text" name="nome" value="{{ old('nome', $template->nome ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                    @error('nome') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                    <select name="tipo" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                        @foreach(['vencimento','cobranca','interessados','pagamento','avulsa'] as $tipo)
                        <option value="{{ $tipo }}" {{ old('tipo', $template->tipo ?? '') == $tipo ? 'selected' : '' }}>{{ ucfirst($tipo) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Canal *</label>
                    <select name="canal" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                        @foreach(['email','whatsapp','sms','telegram'] as $canal)
                        <option value="{{ $canal }}" {{ old('canal', $template->canal ?? 'email') == $canal ? 'selected' : '' }}>{{ ucfirst($canal) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Assunto</label>
                    <input type="text" name="assunto" value="{{ old('assunto', $template->assunto ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Conteudo *</label>
                <textarea name="conteudo" rows="8" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">{{ old('conteudo', $template->conteudo ?? '') }}</textarea>
                @error('conteudo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="ativo" value="1" {{ old('ativo', $template->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                    Ativo
                </label>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('comunicacao.templates.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700">Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
