@extends('layouts.app')
@section('title', 'Cadastro de Diploma Digital')

@php
$badges = [
    'pendente' => 'bg-amber-100 text-amber-700',
    'emitido' => 'bg-blue-100 text-blue-700',
    'assinado' => 'bg-indigo-100 text-indigo-700',
    'registrado' => 'bg-green-100 text-green-700',
];
@endphp

@section('content')
<div class="bg-white rounded-xl border" x-data="{ filtros: {{ $request->hasAny(['situacao','matricula_id','solicitacao_inicio','registro_inicio']) ? 'true' : 'false' }} }">
    <div class="px-5 py-3 border-b flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">215</span>
            <div>
                <h1 class="text-lg font-semibold text-gray-800">Cadastro de Diploma Digital</h1>
                <p class="text-xs text-gray-400">Acadêmico › Diploma Digital</p>
            </div>
        </div>
        <button @click="filtros = !filtros" class="px-3 py-1.5 border rounded-lg text-sm text-gray-600 hover:bg-gray-50"><i class="fa-solid fa-filter mr-1"></i> Filtros</button>
    </div>

    {{-- Filtros (fiel ao EDUQ) --}}
    <div x-show="filtros" x-cloak class="p-5 border-b bg-gray-50">
        <form method="GET" action="{{ route('ged.diplomas.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Situação do Diploma Digital</label>
                <select name="situacao" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Todas...</option>
                    @foreach($situacoes as $val=>$lbl)<option value="{{ $val }}" @selected($request->situacao===$val)>{{ $lbl }}</option>@endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-500 mb-1">Matrícula</label>
                <select name="matricula_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Todas...</option>
                    @foreach($matriculas as $m)<option value="{{ $m->id }}" @selected((string)$request->matricula_id===(string)$m->id)>{{ $m->numero_matricula ?? ('#'.$m->id) }} — {{ $m->aluno?->pessoa?->nome ?? 'Aluno '.$m->aluno_id }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Data de solicitação</label>
                <div class="flex gap-2">
                    <input type="date" name="solicitacao_inicio" value="{{ $request->solicitacao_inicio }}" class="w-full border rounded-lg px-2 py-2 text-sm">
                    <input type="date" name="solicitacao_fim" value="{{ $request->solicitacao_fim }}" class="w-full border rounded-lg px-2 py-2 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Data de registro</label>
                <div class="flex gap-2">
                    <input type="date" name="registro_inicio" value="{{ $request->registro_inicio }}" class="w-full border rounded-lg px-2 py-2 text-sm">
                    <input type="date" name="registro_fim" value="{{ $request->registro_fim }}" class="w-full border rounded-lg px-2 py-2 text-sm">
                </div>
            </div>
            <div class="flex items-end gap-2">
                <a href="{{ route('ged.diplomas.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-white"><i class="fa-solid fa-eraser mr-1"></i> Limpar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-filter mr-1"></i> Filtrar</button>
            </div>
        </form>
    </div>

    <div class="p-4">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Matrícula</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Curso</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nº Registro</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Solicitação</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situação</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($diplomas as $d)
                @php $nome = $d->matricula?->aluno?->pessoa?->nome ?? $d->aluno?->pessoa?->nome ?? '—'; $numMat = $d->matricula?->numero_matricula; @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $numMat ? $numMat.' — ' : '' }}{{ $nome }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $d->curso?->nome ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $d->numero_registro ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $d->data_solicitacao?->format('d/m/Y') ?? '—' }}</td>
                    <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full {{ $badges[$d->situacao] ?? 'bg-gray-100 text-gray-500' }}">{{ $situacoes[$d->situacao] ?? $d->situacao }}</span></td>
                    <td class="px-4 py-3">
                        <div class="flex gap-1">
                            <a href="{{ route('ged.diplomas.edit', $d) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                            <form method="POST" action="{{ route('ged.diplomas.destroy', $d) }}" onsubmit="return confirm('Remover?')">
                                @csrf @method('DELETE')
                                <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhum diploma encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $diplomas->links() }}</div>
    </div>
</div>
<x-fab :route="route('ged.diplomas.create')" label="Novo Diploma" />
@endsection
