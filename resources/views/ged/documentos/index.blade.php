@extends('layouts.app')
@section('title', 'Documentos GED')

@section('content')
<x-data-table title="Documentos GED" codigo="218" :createRoute="route('ged.documentos.create')" createLabel="Enviar Documento">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Título</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Classificação</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Enviado em</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($documentos as $d)
            <tr class="hover:bg-gray-50">
                <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $d->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 text-gray-500">{{ $d->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $d->titulo }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $d->classificacao?->nome ?? '—' }}</td>
                <td class="px-4 py-3"><span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full uppercase">{{ $d->tipo_arquivo ?? '—' }}</span></td>
                <td class="px-4 py-3 text-gray-500">{{ $d->created_at?->format('d/m/Y H:i') }}</td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('ged.documentos.edit', $d)" :delete="route('ged.documentos.destroy', $d)">@if($d->arquivo)
                        <a href="{{ Storage::url($d->arquivo) }}" target="_blank" class="p-1.5 text-green-600 hover:bg-green-50 rounded" title="Baixar"><i class="fa-solid fa-download"></i></a>
                        @endif</x-kebab>
                        </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Nenhum documento enviado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $documentos->links() }}</div>
</x-data-table>
@endsection
