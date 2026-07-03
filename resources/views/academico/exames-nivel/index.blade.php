@extends('layouts.app')
@section('title', 'Manutenção de Exame de Nível')

@section('content')
<x-data-table title="Manutenção de Exame de Nível" codigo="183" :createRoute="route('academico.exames-nivel.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Aluno</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Disciplina</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nota</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situação</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Data</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($registros as $r)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $r->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $r->aluno?->pessoa?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $r->disciplina?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $r->nota !== null ? number_format($r->nota, 2, ',', '.') : '—' }}</td>
                <td class="px-4 py-3">
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $r->situacao === 'Aprovado' ? 'bg-green-100 text-green-700' : ($r->situacao === 'Reprovado' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">{{ $r->situacao }}</span>
                </td>
                <td class="px-4 py-3 text-gray-600">{{ optional($r->data_exame)->format('d/m/Y') ?? '—' }}</td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('academico.exames-nivel.edit', $r)" :delete="route('academico.exames-nivel.destroy', $r)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Nenhum exame registrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $registros->links() }}</div>
</x-data-table>
@endsection
