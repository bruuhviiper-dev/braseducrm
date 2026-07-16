@extends('layouts.app')
@section('title', 'Plano de Contas')

@section('content')
<div class="bg-white rounded-xl border">
    <div class="p-5 border-b flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">50</span>
            <h1 class="text-lg font-semibold text-gray-800">Plano de Contas</h1>
        </div>
    </div>

    <div class="p-4">
        @if($contas->count() > 0)
        <div class="space-y-1">
            @foreach($contas as $conta)
                @include('financeiro.plano-contas._tree-item', ['conta' => $conta, 'nivel' => 0])
            @endforeach
        </div>
        @else
        <div class="text-center py-8 text-gray-400">
            <i class="fa-solid fa-sitemap text-3xl mb-2"></i>
            <p>Nenhuma conta cadastrada.</p>
        </div>
        @endif
    </div>
</div>
<x-fab :route="route('financeiro.plano-contas.create')" />
@endsection