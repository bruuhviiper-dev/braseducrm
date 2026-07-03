@extends('layouts.app')
@section('title', 'Cadastro de Pessoa')

@php
    $cores = ['bg-pink-500','bg-indigo-500','bg-emerald-500','bg-amber-500','bg-cyan-500','bg-purple-500','bg-rose-500','bg-teal-500'];
    $iniciais = function ($nome) {
        $p = preg_split('/\s+/', trim((string) $nome));
        $a = mb_substr($p[0] ?? '', 0, 1);
        $b = mb_substr($p[1] ?? ($p[0] ?? ''), 0, 1);
        return mb_strtoupper($a . $b);
    };
@endphp

@section('content')
<x-data-table title="Cadastro de Pessoa" codigo="11" breadcrumb="Geral › Pessoas" :createRoute="route('pessoas.create')" createLabel="Nova Pessoa">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b bg-gray-50 text-[11px] uppercase tracking-wide text-gray-500">
                    <th class="py-3 px-3 w-10"></th>
                    <th class="text-left py-3 px-2 font-medium whitespace-nowrap">{{ $pessoas->total() }} registros</th>
                    <th class="text-left py-3 px-2 font-medium w-16">Ações</th>
                    <th class="text-left py-3 px-4 font-medium">Nome</th>
                    <th class="text-left py-3 px-4 font-medium">Documento</th>
                    <th class="text-left py-3 px-4 font-medium">Dt. últ. atualização</th>
                    <th class="text-left py-3 px-4 font-medium">Tipo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pessoas as $pessoa)
                <tr class="border-b hover:bg-gray-50">
                    {{-- radio de seleção (EDUQ) --}}
                    <td class="py-3 px-3">
                        <input type="radio" name="sel_pessoa" value="{{ $pessoa->id }}" class="w-4 h-4 text-primary-600 border-gray-300">
                    </td>
                    <td class="py-3 px-2 text-gray-500">{{ $pessoa->id }}</td>
                    {{-- kebab ⋮ de ações (EDUQ) --}}
                    <td class="py-3 px-2">
                        <div x-data="{ o: false }" class="relative">
                            <button @click="o = !o" class="w-8 h-8 border rounded-lg text-gray-500 hover:bg-gray-100 flex items-center justify-center">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div x-show="o" x-cloak @click.away="o = false"
                                 class="absolute left-0 mt-1 w-40 bg-white border rounded-lg shadow-xl z-20 py-1">
                                <a href="{{ route('pessoas.show', $pessoa) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fa-solid fa-eye mr-2 text-gray-400"></i>Visualizar</a>
                                <a href="{{ route('pessoas.edit', $pessoa) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fa-solid fa-pen mr-2 text-gray-400"></i>Editar</a>
                                <form method="POST" action="{{ route('pessoas.destroy', $pessoa) }}" onsubmit="return confirm('Deseja realmente excluir esta pessoa?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i class="fa-solid fa-trash mr-2"></i>Excluir</button>
                                </form>
                            </div>
                        </div>
                    </td>
                    {{-- avatar iniciais + nome (EDUQ) --}}
                    <td class="py-3 px-4">
                        <a href="{{ route('pessoas.show', $pessoa) }}" class="flex items-center gap-3 group">
                            <span class="w-8 h-8 rounded-full {{ $cores[$pessoa->id % count($cores)] }} text-white text-xs font-bold flex items-center justify-center shrink-0">{{ $iniciais($pessoa->nome) }}</span>
                            <span class="font-medium text-gray-800 group-hover:text-primary-600">{{ $pessoa->nome }}</span>
                        </a>
                    </td>
                    <td class="py-3 px-4 text-gray-600">{{ $pessoa->cpf ?? $pessoa->cnpj ?? '—' }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $pessoa->updated_at?->format('d/m/Y H:i') ?? '—' }}</td>
                    <td class="py-3 px-4">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-600 border">
                            <i class="fa-solid fa-user text-[10px]"></i> Pessoa
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-10 text-center text-gray-400">
                        <i class="fa-solid fa-box-open text-3xl mb-2"></i>
                        <p>Nada encontrado. Nenhum item encontrado.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $pessoas->links() }}
    </div>
</x-data-table>
@endsection
