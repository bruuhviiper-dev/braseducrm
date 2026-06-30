@extends('layouts.app')
@section('title', 'Manutenção de Exame de Nível')

@section('content')
<x-data-table title="Manutenção de Exame de Nível" codigo="183" :createRoute="route('academico.exames-nivel.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
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
                <td class="px-4 py-3 font-medium text-gray-800">{{ $r->aluno?->pessoa?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $r->disciplina?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $r->nota !== null ? number_format($r->nota, 2, ',', '.') : '—' }}</td>
                <td class="px-4 py-3">
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $r->situacao === 'Aprovado' ? 'bg-green-100 text-green-700' : ($r->situacao === 'Reprovado' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">{{ $r->situacao }}</span>
                </td>
                <td class="px-4 py-3 text-gray-600">{{ optional($r->data_exame)->format('d/m/Y') ?? '—' }}</td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('academico.exames-nivel.edit', $r) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('academico.exames-nivel.destroy', $r) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhum exame registrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $registros->links() }}</div>
</x-data-table>
@endsection
