@extends('layouts.app')
@section('title', 'Matrícula e Histórico')

@php
$badge = function ($s) {
    return match ($s) {
        'confirmada', 'ativa' => ['bg-cyan-100 text-cyan-700', 'Confirmada'],
        'nao_confirmada' => ['bg-orange-100 text-orange-600', 'Não Confirmada'],
        'trancada' => ['bg-yellow-100 text-yellow-700', 'Trancado'],
        'cancelada' => ['bg-red-100 text-red-600', 'Cancelada'],
        'desistente' => ['bg-red-100 text-red-600', 'Desistente'],
        'concluida' => ['bg-green-100 text-green-700', 'Concluída'],
        'dependencia' => ['bg-purple-100 text-purple-700', 'Dependência'],
        default => ['bg-gray-100 text-gray-600', ucfirst(str_replace('_', ' ', $s ?? '-'))],
    };
};
@endphp

@section('content')
<x-data-table title="Matrícula e Histórico" codigo="23" :createRoute="route('academico.matriculas.create')">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Aluno</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Curso / Turma</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Operador</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Contrato</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situação</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($matriculas as $matricula)
                @php $pessoa = $matricula->aluno?->pessoa; [$cls, $rotulo] = $badge($matricula->situacao); @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2.5">
                        <a href="{{ route('academico.matriculas.ficha', $matricula) }}" class="flex items-center gap-3 group">
                            <span class="w-9 h-9 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-xs font-bold shrink-0 overflow-hidden">
                                @if($pessoa?->foto)<img src="{{ asset('storage/'.$pessoa->foto) }}" class="object-cover w-full h-full" alt="">
                                @else{{ mb_strtoupper(mb_substr($pessoa?->nome ?? '?', 0, 1)) }}@endif
                            </span>
                            <span>
                                <span class="block font-semibold text-gray-800 group-hover:text-cyan-600">{{ $pessoa?->nome ?? '-' }}</span>
                                <span class="block text-xs text-gray-400">{{ $matricula->numero_matricula ?? '#'.$matricula->id }}</span>
                                @if($pessoa?->cpf)<span class="block text-xs text-gray-400">{{ $pessoa->cpf }}</span>@endif
                            </span>
                        </a>
                    </td>
                    <td class="px-4 py-2.5">
                        <x-kebab :edit="route('academico.matriculas.ficha', $matricula)" :delete="route('academico.matriculas.destroy', $matricula)">
                            <a href="{{ route('academico.matriculas.ficha', $matricula) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fa-regular fa-folder-open mr-2 text-gray-400"></i>Abrir</a>
                            <a href="{{ route('academico.matriculas.edit', $matricula) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fa-regular fa-pen-to-square mr-2 text-gray-400"></i>Editar cadastro</a>
                            <a href="{{ route('academico.matriculas.historico', $matricula) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fa-solid fa-clock-rotate-left mr-2 text-gray-400"></i>Histórico Escolar</a>
                        </x-kebab>
                    </td>
                    <td class="px-4 py-2.5 text-gray-600">
                        <span class="block">{{ $matricula->turmaMontada?->sigla ? $matricula->turmaMontada->sigla.' - ' : '' }}{{ $matricula->turma?->curso?->nome ?? $matricula->turma?->nome ?? '-' }}</span>
                        <span class="block text-xs text-gray-400">{{ $matricula->turma?->nome }}</span>
                    </td>
                    <td class="px-4 py-2.5 text-gray-600">
                        <span class="inline-flex items-center gap-1.5"><span class="w-6 h-6 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center text-[10px] font-bold">{{ mb_strtoupper(mb_substr($matricula->consultor?->nome ?? 'A', 0, 1)) }}</span>{{ $matricula->consultor?->nome ?? 'Administrador' }}</span>
                    </td>
                    <td class="px-4 py-2.5">
                        @php $temAssinado = $matricula->assinaturasEletronicas->isNotEmpty() && $matricula->assinaturasEletronicas->every(fn ($a) => $a->situacao === 'assinado'); @endphp
                        <span class="block text-xs font-medium text-cyan-700 bg-cyan-50 border border-cyan-200 rounded px-2 py-1 w-max"><i class="fa-regular fa-file-lines mr-1"></i>Contrato ({{ $matricula->turma?->curso?->nome ? Str::limit($matricula->turma->curso->nome, 14) : 'Curso' }})</span>
                        <span class="block text-xs {{ $temAssinado ? 'text-green-600' : 'text-gray-400' }} mt-0.5">{{ $matricula->assinaturasEletronicas->isEmpty() ? 'Pendente' : ($temAssinado ? 'Assinado' : 'Enviado') }}</span>
                    </td>
                    <td class="px-4 py-2.5">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $cls }}">{{ $rotulo }}</span>
                        <span class="block text-xs text-gray-400 mt-0.5">Início: {{ $matricula->data_inicio_aulas?->format('d/m/Y') ?? $matricula->data_matricula?->format('d/m/Y') ?? '-' }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Nenhuma matrícula encontrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $matriculas->links() }}
    </div>
</x-data-table>
@endsection
