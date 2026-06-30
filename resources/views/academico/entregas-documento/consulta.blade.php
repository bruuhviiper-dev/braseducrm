@extends('layouts.app')
@section('title', 'Consulta Documentos não Entregues')

@section('content')
<div class="space-y-4">
    <div class="bg-white rounded-xl border p-5">
        <div class="flex items-center gap-3 mb-4">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">102</span>
            <h1 class="text-lg font-semibold text-gray-800">Consulta Documentos não Entregues</h1>
        </div>
        <form method="GET" action="{{ route('academico.entregas-documento.consulta') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <input type="hidden" name="consultar" value="1">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Curso</label>
                <select name="curso_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Todos</option>
                    @foreach($cursos as $c)<option value="{{ $c->id }}" {{ (string)request('curso_id') === (string)$c->id ? 'selected' : '' }}>{{ $c->nome }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Situação da Matrícula</label>
                <select name="situacao" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Todas</option>
                    @foreach(['ativa','trancada','cancelada','concluida'] as $s)<option value="{{ $s }}" {{ request('situacao')===$s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>@endforeach
                </select>
            </div>
            <div>
                <button class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-magnifying-glass mr-1"></i> Consultar Documentos</button>
            </div>
        </form>
    </div>

    @if(request()->boolean('consultar'))
    <div class="bg-white rounded-xl border">
        <div class="p-4">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Matrícula</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Aluno</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Curso</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Documentos Pendentes</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($pendencias as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $p['matricula']->numero_matricula ?? $p['matricula']->id }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $p['matricula']->aluno?->pessoa?->nome ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $p['matricula']->turma?->curso?->nome ?? '—' }}</td>
                        <td class="px-4 py-3 text-red-600">{{ $p['faltantes'] }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhuma pendência encontrada com os filtros.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
