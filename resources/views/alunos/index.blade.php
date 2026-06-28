@extends('layouts.app')
@section('title', 'Alunos')

@section('content')
<x-data-table title="Alunos" codigo="17" :createRoute="route('alunos.create')" createLabel="Novo Aluno">
    {{-- Search --}}
    <form method="GET" action="{{ route('alunos.index') }}" class="mb-4">
        <div class="relative max-w-md">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Buscar por nome ou RA..."
                   class="w-full pl-10 pr-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">ID</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">RA</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Nome</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Data Ingresso</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Forma Ingresso</th>
                    <th class="text-center py-3 px-4 font-semibold text-gray-600">Ativo</th>
                    <th class="text-center py-3 px-4 font-semibold text-gray-600">Acoes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alunos as $aluno)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4 text-gray-500">{{ $aluno->id }}</td>
                    <td class="py-3 px-4 font-medium text-gray-800">{{ $aluno->ra ?? '-' }}</td>
                    <td class="py-3 px-4">
                        <a href="{{ route('pessoas.show', $aluno->pessoa_id) }}" class="text-primary-600 hover:underline font-medium">
                            {{ $aluno->pessoa->nome ?? '-' }}
                        </a>
                    </td>
                    <td class="py-3 px-4 text-gray-600">{{ $aluno->data_ingresso ? $aluno->data_ingresso->format('d/m/Y') : '-' }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $aluno->formaIngresso->nome ?? '-' }}</td>
                    <td class="py-3 px-4 text-center">
                        @if($aluno->ativo)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Sim</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Nao</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('emissoes.historico', $aluno) }}" target="_blank" class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded" title="Histórico Escolar (PDF)">
                                <i class="fa-solid fa-file-pdf"></i>
                            </a>
                            <a href="{{ route('alunos.edit', $aluno) }}" class="p-1.5 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded" title="Editar">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('alunos.destroy', $aluno) }}" class="inline"
                                  onsubmit="return confirm('Deseja realmente excluir este aluno?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded" title="Excluir">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 text-center text-gray-400">
                        <i class="fa-solid fa-graduation-cap text-3xl mb-2"></i>
                        <p>Nenhum aluno encontrado.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $alunos->links() }}
    </div>
</x-data-table>
@endsection
