@extends('layouts.app')
@section('title', 'Entrega de Documentos')

@section('content')
<div class="bg-white rounded-xl border">
    <div class="p-5 border-b flex items-center gap-3">
        <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">19</span>
        <h1 class="text-lg font-semibold text-gray-800">Entrega de Documentos</h1>
    </div>
    <div class="p-4">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Matrícula</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Aluno</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Curso</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($matriculas as $m)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $m->numero_matricula ?? $m->id }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $m->aluno?->pessoa?->nome ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $m->turma?->curso?->nome ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('academico.entregas-documento.gerenciar', $m) }}" class="px-2.5 py-1 bg-primary-600 text-white rounded text-xs hover:bg-primary-700"><i class="fa-solid fa-folder-open mr-1"></i> Documentos</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhuma matrícula encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $matriculas->links() }}</div>
    </div>
</div>
@endsection
