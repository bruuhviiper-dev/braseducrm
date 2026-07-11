@extends('layouts.app')
@section('title', 'Controle de Horas Complementares')

@section('content')
<x-data-table title="Controle de Horas Complementares" codigo="239" :createRoute="route('academico.horas-complementares.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Matrícula</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Quantidade</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situação</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($registros as $r)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $r->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $r->matricula?->rotulo ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ number_format($r->quantidade, 2, ',', '.') }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $r->tipo }}</td>
                <td class="px-4 py-3">
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $r->situacao === 'Aprovado' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">{{ $r->situacao }}</span>
                </td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('academico.horas-complementares.edit', $r)" :delete="route('academico.horas-complementares.destroy', $r)">
                        @if($r->situacao !== 'Aprovado')
                        <form method="POST" action="{{ route('academico.horas-complementares.aprovar', $r) }}" class="inline">
                            @csrf <input type="hidden" name="decisao" value="aprovar">
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-green-700 hover:bg-green-50"><i class="fa-solid fa-check mr-2"></i>Aprovar horas</button>
                        </form>
                        @endif
                        @if($r->situacao === 'Aprovado' || $r->situacao === 'Pendente')
                        <button type="button" x-data onclick="document.getElementById('recusa-{{ $r->id }}').showModal()" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50"><i class="fa-solid fa-xmark mr-2"></i>Recusar</button>
                        @endif
                    </x-kebab>
                    <dialog id="recusa-{{ $r->id }}" class="rounded-xl shadow-xl p-6 w-full max-w-md backdrop:bg-black/40">
                        <form method="POST" action="{{ route('academico.horas-complementares.aprovar', $r) }}" class="space-y-3">
                            @csrf <input type="hidden" name="decisao" value="recusar">
                            <p class="font-semibold text-sm text-gray-800">Recusar horas complementares</p>
                            <textarea name="motivo_recusa" required rows="2" placeholder="Informe o motivo para o aluno reenviar o certificado..." class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="this.closest('dialog').close()" class="px-4 py-2 border rounded-lg text-sm text-gray-700">Cancelar</button>
                                <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-semibold">Recusar</button>
                            </div>
                        </form>
                    </dialog>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhum lançamento.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $registros->links() }}</div>
</x-data-table>
@endsection
