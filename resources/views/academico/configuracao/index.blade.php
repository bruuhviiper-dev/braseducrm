@extends('layouts.app')
@section('title', 'Configuração do Acadêmico')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center gap-3 px-6 py-4 border-b">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">167</span>
            <h2 class="text-base font-semibold text-gray-800">Configuração do Acadêmico</h2>
        </div>
        <form method="POST" action="{{ route('academico.configuracao.index') }}" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <label class="flex items-start gap-3">
                <input type="checkbox" name="assinatura_eletronica" value="1" {{ old('assinatura_eletronica', $config->assinatura_eletronica) ? 'checked' : '' }} class="mt-0.5 rounded border-gray-300 text-blue-600">
                <span><span class="block text-sm font-medium text-gray-700">Assinatura eletrônica em documentos</span><span class="block text-xs text-gray-400">Habilita assinatura digital em contratos e declarações.</span></span>
            </label>
            <label class="flex items-start gap-3">
                <input type="checkbox" name="envio_email_matricula" value="1" {{ old('envio_email_matricula', $config->envio_email_matricula) ? 'checked' : '' }} class="mt-0.5 rounded border-gray-300 text-blue-600">
                <span><span class="block text-sm font-medium text-gray-700">Enviar e-mail ao matricular</span><span class="block text-xs text-gray-400">Dispara e-mail automático na confirmação de matrícula.</span></span>
            </label>
            <label class="flex items-start gap-3">
                <input type="checkbox" name="aniversariante_automatico" value="1" {{ old('aniversariante_automatico', $config->aniversariante_automatico) ? 'checked' : '' }} class="mt-0.5 rounded border-gray-300 text-blue-600">
                <span><span class="block text-sm font-medium text-gray-700">Mensagem de aniversário automática</span><span class="block text-xs text-gray-400">Envia felicitação aos aniversariantes do dia.</span></span>
            </label>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Template do e-mail de matrícula</label>
                <textarea name="email_matricula_template" rows="4" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('email_matricula_template', $config->email_matricula_template) }}</textarea>
            </div>

            <div class="border-t pt-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">Salvar Configuração</button>
            </div>
        </form>
    </div>
</div>
@endsection
