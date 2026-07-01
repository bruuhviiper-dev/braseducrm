@extends('layouts.app')
@section('title', 'Consulta de Estoque')

@section('content')
<div class="space-y-4">
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-white rounded-xl border p-4"><p class="text-xs text-gray-500 uppercase">Itens cadastrados</p><p class="text-2xl font-bold text-gray-800">{{ $stats['itens'] }}</p></div>
        <div class="bg-white rounded-xl border p-4"><p class="text-xs text-gray-500 uppercase">Abaixo do mínimo</p><p class="text-2xl font-bold text-red-600">{{ $stats['abaixo_minimo'] }}</p></div>
        <div class="bg-white rounded-xl border p-4"><p class="text-xs text-gray-500 uppercase">Valor em estoque</p><p class="text-2xl font-bold text-gray-800">R$ {{ number_format($stats['valor_total'], 2, ',', '.') }}</p></div>
    </div>

    <div class="bg-white rounded-xl border">
        <div class="p-5 border-b flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">154</span>
                <h1 class="text-lg font-semibold text-gray-800">Consulta de Estoque</h1>
            </div>
            <a href="{{ route('estoque.emissao', request()->only('categoria')) }}" target="_blank" class="px-3 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700"><i class="fa-solid fa-file-pdf mr-1"></i> Emitir PDF</a>
        </div>
        <div class="p-4">
            <form method="GET" class="flex flex-wrap gap-2 mb-4">
                <input type="text" name="busca" value="{{ request('busca') }}" placeholder="Nome ou código..." class="border rounded-lg px-3 py-2 text-sm">
                <select name="categoria" class="border rounded-lg px-3 py-2 text-sm">
                    <option value="">Todas as categorias</option>
                    @foreach($categorias as $c)<option value="{{ $c->id }}" @selected(request('categoria')==$c->id)>{{ $c->nome }}</option>@endforeach
                </select>
                <label class="flex items-center gap-2 text-sm text-gray-600 px-2"><input type="checkbox" name="abaixo_minimo" value="1" @checked(request('abaixo_minimo')) class="rounded border-gray-300"> Só abaixo do mínimo</label>
                <button class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm hover:bg-primary-700">Filtrar</button>
            </form>
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Código</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Produto</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Categoria</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Estoque</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Mínimo</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Custo</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($produtos as $p)
                    <tr class="hover:bg-gray-50 {{ $p->estoque_atual <= $p->estoque_minimo ? 'bg-red-50' : '' }}">
                        <td class="px-4 py-3 text-gray-600">{{ $p->codigo ?? '—' }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $p->nome }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $p->categoriaEstoque?->nome ?? '—' }}</td>
                        <td class="px-4 py-3 {{ $p->estoque_atual <= $p->estoque_minimo ? 'text-red-600 font-semibold' : 'text-gray-800' }}">{{ $p->estoque_atual }} {{ $p->unidadeMedida?->sigla ?? '' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $p->estoque_minimo }}</td>
                        <td class="px-4 py-3 text-gray-600">R$ {{ number_format($p->preco_custo, 2, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhum produto encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">{{ $produtos->links() }}</div>
        </div>
    </div>
</div>
@endsection
