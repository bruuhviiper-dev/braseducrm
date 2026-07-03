@extends('layouts.app')
@section('title', 'Periodos Letivos')

@section('content')
<x-data-table title="Periodos Letivos" codigo="200" :createRoute="route('academico.periodos-letivos.create')">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50">
                <tr>
                <th class="py-3 px-3 w-10"></th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Data Inicio</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Data Fim</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($periodos as $periodo)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $periodo->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                    <td class="px-4 py-3 text-gray-600">{{ $periodo->id }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $periodo->nome }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $periodo->data_inicio ? $periodo->data_inicio->format('d/m/Y') : '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $periodo->data_fim ? $periodo->data_fim->format('d/m/Y') : '-' }}</td>
                    <td class="px-4 py-3">
                        @if($periodo->ativo)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Ativo</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inativo</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <x-kebab :edit="route('academico.periodos-letivos.edit', $periodo)" :delete="route('academico.periodos-letivos.destroy', $periodo)" />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">Nenhum periodo letivo encontrado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $periodos->links() }}
    </div>
</x-data-table>
@endsection
