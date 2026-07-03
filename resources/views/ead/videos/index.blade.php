@extends('layouts.app')
@section('title', 'Cadastro de Vídeos')

@section('content')
<x-data-table title="Cadastro de Vídeos" codigo="301" :createRoute="route('ead.videos.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Título</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Descrição</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Arquivo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($videos as $v)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $v->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $v->titulo }}</td>
                <td class="px-4 py-3 text-gray-600">{{ \Illuminate\Support\Str::limit($v->descricao, 60) }}</td>
                <td class="px-4 py-3">
                    @if($v->arquivo)<a href="{{ asset('storage/'.$v->arquivo) }}" target="_blank" class="text-blue-600 hover:underline text-xs"><i class="fa-solid fa-play mr-1"></i>Ver vídeo</a>
                    @else<span class="text-gray-400 text-xs">—</span>@endif
                </td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('ead.videos.edit', $v)" :delete="route('ead.videos.destroy', $v)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhum vídeo cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $videos->links() }}</div>
</x-data-table>
@endsection
