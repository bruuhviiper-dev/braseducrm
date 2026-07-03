@extends('layouts.app')
@section('title', 'Departamentos')

@section('content')
<x-data-table title="Cadastro de Departamento" codigo="67" :createRoute="route('admin.departamentos.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Operadores</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($departamentos as $d)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $d->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 text-gray-500">{{ $d->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $d->nome }}</td>
                <td class="px-4 py-3"><span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $d->users_count }}</span></td>
                <td class="px-4 py-3">
                    @if($d->ativo)
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Ativo</span>
                    @else
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Inativo</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('admin.departamentos.edit', $d)" :delete="route('admin.departamentos.destroy', $d)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhum departamento cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $departamentos->links() }}</div>
</x-data-table>
@endsection
