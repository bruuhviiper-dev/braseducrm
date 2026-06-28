@extends('layouts.app')
@section('title', 'GED - Gestão Eletrônica de Documentos')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3">
        <i class="fa-solid fa-folder-open text-xl text-primary-500"></i>
        <h1 class="text-lg font-semibold text-gray-800">GED — Gestão Eletrônica de Documentos</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('ged.documentos.index') }}" class="bg-white rounded-xl border p-5 hover:shadow-md transition flex items-center gap-4">
            <div class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center"><i class="fa-solid fa-file-arrow-up text-xl text-primary-500"></i></div>
            <div>
                <div class="text-sm font-semibold text-gray-800">Documentos GED</div>
                <div class="text-xs text-gray-500">{{ $stats['documentos'] }} arquivos</div>
            </div>
        </a>
        <a href="{{ route('ged.classificacoes.index') }}" class="bg-white rounded-xl border p-5 hover:shadow-md transition flex items-center gap-4">
            <div class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center"><i class="fa-solid fa-sitemap text-xl text-primary-500"></i></div>
            <div>
                <div class="text-sm font-semibold text-gray-800">Classificação GED <span class="text-xs text-gray-400">(218)</span></div>
                <div class="text-xs text-gray-500">{{ $stats['classificacoes'] }} classificações</div>
            </div>
        </a>
        <a href="{{ route('ged.atos.index') }}" class="bg-white rounded-xl border p-5 hover:shadow-md transition flex items-center gap-4">
            <div class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center"><i class="fa-solid fa-file-shield text-xl text-primary-500"></i></div>
            <div>
                <div class="text-sm font-semibold text-gray-800">Atos Regulatórios <span class="text-xs text-gray-400">(216)</span></div>
                <div class="text-xs text-gray-500">{{ $stats['atos'] }} atos</div>
            </div>
        </a>
        <a href="{{ route('ged.diplomas.index') }}" class="bg-white rounded-xl border p-5 hover:shadow-md transition flex items-center gap-4">
            <div class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center"><i class="fa-solid fa-award text-xl text-primary-500"></i></div>
            <div>
                <div class="text-sm font-semibold text-gray-800">Diploma Digital <span class="text-xs text-gray-400">(215)</span></div>
                <div class="text-xs text-gray-500">{{ $stats['diplomas'] }} diplomas</div>
            </div>
        </a>
    </div>

    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-600 mb-3">Outras funções do módulo</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <div class="flex items-center gap-2 p-2.5 border rounded-lg text-sm text-gray-400">
                <span class="font-semibold min-w-[28px]">226</span><span>Histórico Escolar Digital</span>
            </div>
        </div>
    </div>
</div>
@endsection
