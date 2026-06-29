@extends('layouts.app')
@section('title', 'Mensagem Avulsa')

@section('content')
<div class="max-w-2xl mx-auto" x-data="{
    aplicarTemplate(sel) {
        const opt = sel.options[sel.selectedIndex];
        if (opt.dataset.conteudo) document.querySelector('[name=conteudo]').value = opt.dataset.conteudo;
        if (opt.dataset.assunto) document.querySelector('[name=assunto]').value = opt.dataset.assunto;
        if (opt.dataset.canal) document.querySelector('[name=canal]').value = opt.dataset.canal;
    }
}">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">Mensagem Avulsa</h2>
            <a href="{{ route('comunicacao.mensagens.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ route('comunicacao.mensagens.avulsa.store') }}" class="p-6 space-y-4">
            @csrf

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Template (opcional)</label>
                <select name="template_id" @change="aplicarTemplate($el)" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">— Sem template —</option>
                    @foreach($templates as $t)
                    <option value="{{ $t->id }}" data-conteudo="{{ $t->conteudo }}" data-assunto="{{ $t->assunto }}" data-canal="{{ $t->canal }}">{{ $t->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pessoa (opcional)</label>
                    <select name="pessoa_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">—</option>
                        @foreach($pessoas as $p)
                        <option value="{{ $p->id }}">{{ $p->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Canal <span class="text-red-500">*</span></label>
                    <select name="canal" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="email">E-mail</option>
                        <option value="sms">SMS</option>
                        <option value="whatsapp">WhatsApp</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Destinatário <span class="text-red-500">*</span></label>
                <input type="text" name="destinatario" value="{{ old('destinatario') }}" placeholder="e-mail ou telefone" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assunto</label>
                <input type="text" name="assunto" value="{{ old('assunto') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Conteúdo <span class="text-red-500">*</span></label>
                <textarea name="conteudo" rows="5" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('conteudo') }}</textarea>
            </div>

            <div class="bg-amber-50 border border-amber-200 text-amber-700 px-3 py-2 rounded text-xs">
                <i class="fa-solid fa-circle-info mr-1"></i> O envio real depende das integrações configuradas (SMTP para e-mail, SMS/WhatsApp em Integrações). Sem configuração, a mensagem fica registrada com status "erro".
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700"><i class="fa-solid fa-paper-plane mr-1"></i> Enviar</button>
                <a href="{{ route('comunicacao.mensagens.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
