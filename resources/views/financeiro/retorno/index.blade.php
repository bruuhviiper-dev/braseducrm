@extends('layouts.app')
@section('title', 'Importação de Retorno Bancário')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center gap-3 px-6 py-4 border-b">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">82</span>
            <h2 class="text-base font-semibold text-gray-800">Importação do Arquivo de Retorno (CNAB)</h2>
        </div>
        <form method="POST" action="{{ route('financeiro.retorno.processar') }}" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <p class="text-sm text-gray-500">Envie o arquivo de retorno (.ret/.txt) do banco no layout CNAB 400. Os títulos liquidados serão baixados automaticamente pelo nosso número.</p>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Arquivo de Retorno <span class="text-red-500">*</span></label>
                <input type="file" name="arquivo" accept=".ret,.txt,.RET,.TXT" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700"><i class="fa-solid fa-file-import mr-1"></i> Processar Retorno</button>
            </div>
        </form>
    </div>
</div>
@endsection
