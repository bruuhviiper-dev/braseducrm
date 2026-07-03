@extends('layouts.app')
@section('title', 'Profissionais')

@section('content')
<x-data-table title="Cadastro de Profissional" codigo="12" :createRoute="route('profissionais.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Titularidade</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Registro</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($profissionais as $p)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $p->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 text-gray-500">{{ $p->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $p->pessoa?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $p->tipoProfissional?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $p->titularidade?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $p->registro_profissional ?? '—' }}</td>
                <td class="px-4 py-3">
                    @if($p->ativo)
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Ativo</span>
                    @else
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Inativo</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('profissionais.edit', $p)" :delete="route('profissionais.destroy', $p)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Nenhum profissional cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $profissionais->links() }}</div>
</x-data-table>
@endsection
