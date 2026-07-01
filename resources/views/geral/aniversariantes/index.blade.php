@extends('layouts.app')
@section('title', 'Aniversariantes')

@section('content')
<div class="bg-white rounded-xl border">
    <div class="p-5 border-b flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">164</span>
            <h1 class="text-lg font-semibold text-gray-800">Aniversariantes</h1>
        </div>
        <form method="GET" class="flex items-center gap-2">
            <label class="text-sm text-gray-500">Mês:</label>
            <select name="mes" onchange="this.form.submit()" class="border rounded-lg px-3 py-2 text-sm">
                @foreach($meses as $num => $nome)<option value="{{ $num }}" @selected($mes==$num)>{{ $nome }}</option>@endforeach
            </select>
        </form>
    </div>
    <div class="p-4">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Dia</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Idade</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Contato</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($pessoas as $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-800"><i class="fa-solid fa-cake-candles text-primary-500 mr-1"></i>{{ $p->data_nascimento->format('d/m') }}</td>
                    <td class="px-4 py-3 text-gray-800">{{ $p->nome }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $p->data_nascimento->age }} anos</td>
                    <td class="px-4 py-3 text-gray-600">{{ $p->celular ?? $p->telefone ?? $p->email ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhum aniversariante em {{ $meses[$mes] }}.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
