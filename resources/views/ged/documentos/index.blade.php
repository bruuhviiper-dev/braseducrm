@extends('layouts.app')
@section('title', 'Documentos GED')

@section('content')
<x-data-table title="Documentos GED" codigo="218" :createRoute="route('ged.documentos.create')" createLabel="Enviar Documento">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
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
                <td class="px-4 py-3 text-gray-500">{{ $d->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $d->titulo }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $d->classificacao?->nome ?? '—' }}</td>
                <td class="px-4 py-3"><span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full uppercase">{{ $d->tipo_arquivo ?? '—' }}</span></td>
                <td class="px-4 py-3 text-gray-500">{{ $d->created_at?->format('d/m/Y H:i') }}</td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        @if($d->arquivo)
                        <a href="{{ Storage::url($d->arquivo) }}" target="_blank" class="p-1.5 text-green-600 hover:bg-green-50 rounded" title="Baixar"><i class="fa-solid fa-download"></i></a>
                        @endif
                        <a href="{{ route('ged.documentos.edit', $d) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('ged.documentos.destroy', $d) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhum documento enviado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $documentos->links() }}</div>
</x-data-table>
@endsection
