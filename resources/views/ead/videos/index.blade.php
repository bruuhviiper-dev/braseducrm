@extends('layouts.app')
@section('title', 'Cadastro de Vídeos')

@section('content')
<x-data-table title="Cadastro de Vídeos" codigo="301" :createRoute="route('ead.videos.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Título</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Descrição</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Arquivo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($videos as $v)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $v->titulo }}</td>
                <td class="px-4 py-3 text-gray-600">{{ \Illuminate\Support\Str::limit($v->descricao, 60) }}</td>
                <td class="px-4 py-3">
                    @if($v->arquivo)<a href="{{ asset('storage/'.$v->arquivo) }}" target="_blank" class="text-blue-600 hover:underline text-xs"><i class="fa-solid fa-play mr-1"></i>Ver vídeo</a>
                    @else<span class="text-gray-400 text-xs">—</span>@endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('ead.videos.edit', $v) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('ead.videos.destroy', $v) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhum vídeo cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $videos->links() }}</div>
</x-data-table>
@endsection
