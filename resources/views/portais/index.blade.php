@extends('layouts.app')
@section('title', 'Portais')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3">
        <i class="fa-solid fa-desktop text-xl text-primary-500"></i>
        <h1 class="text-lg font-semibold text-gray-800">Portais</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('portais.configuracao') }}" class="bg-white rounded-xl border p-5 hover:shadow-md transition flex items-center gap-4">
            <div class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center"><i class="fa-solid fa-gear text-xl text-primary-500"></i></div>
            <div>
                <div class="text-sm font-semibold text-gray-800">Configuração Portal Aluno <span class="text-xs text-gray-400">(46)</span></div>
                <div class="text-xs text-gray-500">{{ $config->ativo ? 'Portal ativo' : 'Portal inativo' }}</div>
            </div>
        </a>
        <a href="{{ route('portais.pastas.index') }}" class="bg-white rounded-xl border p-5 hover:shadow-md transition flex items-center gap-4">
            <div class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center"><i class="fa-solid fa-folder text-xl text-primary-500"></i></div>
            <div>
                <div class="text-sm font-semibold text-gray-800">Pastas do Portal <span class="text-xs text-gray-400">(76)</span></div>
                <div class="text-xs text-gray-500">{{ $stats['pastas'] }} pastas</div>
            </div>
        </a>
        <a href="{{ route('portais.publicacoes.index') }}" class="bg-white rounded-xl border p-5 hover:shadow-md transition flex items-center gap-4">
            <div class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center"><i class="fa-solid fa-newspaper text-xl text-primary-500"></i></div>
            <div>
                <div class="text-sm font-semibold text-gray-800">Publicações Portal Aluno <span class="text-xs text-gray-400">(77)</span></div>
                <div class="text-xs text-gray-500">{{ $stats['publicacoes'] }} publicações</div>
            </div>
        </a>
    </div>
</div>
@endsection
