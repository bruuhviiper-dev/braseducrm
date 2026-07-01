@extends('layouts.app')
@section('title', 'Consulta Personalizada')

@section('content')
<x-data-table title="Consulta Personalizada" codigo="221" :createRoute="route('geral.consultas.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Entidade</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Campos</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($consultas as $c)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $c->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ \App\Models\ConsultaPersonalizada::entidades()[$c->entidade]['label'] ?? $c->entidade }}</td>
                <td class="px-4 py-3 text-gray-600">{{ count($c->campos ?? []) }} campo(s)</td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('geral.consultas.executar', $c) }}" class="p-1.5 text-green-600 hover:bg-green-50 rounded" title="Executar"><i class="fa-solid fa-play"></i></a>
                        <a href="{{ route('geral.consultas.edit', $c) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('geral.consultas.destroy', $c) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhuma consulta salva.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $consultas->links() }}</div>
</x-data-table>
@endsection
