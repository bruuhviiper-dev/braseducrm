@extends('layouts.app')
@section('title', 'Frequência e Conteúdo Ministrado')

@section('content')
<div class="space-y-6">
    {{-- Filtro --}}
    <div class="bg-white rounded-xl border">
        <div class="px-5 py-3 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">16</span>
            <h1 class="text-lg font-semibold text-gray-800">Frequência e Conteúdo Ministrado</h1>
        </div>
        <form method="GET" action="{{ route('academico.frequencia.index') }}" class="p-4 grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Turma Montada</label>
                <select name="turma_montada_id" class="w-full border rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Selecione...</option>
                    @foreach($turmasMontadas as $tm)
                    <option value="{{ $tm->id }}" {{ $request->turma_montada_id == $tm->id ? 'selected' : '' }}>{{ $tm->nome ?? $tm->turma?->nome ?? 'Turma '.$tm->id }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Disciplina</label>
                <select name="disciplina_id" class="w-full border rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Selecione...</option>
                    @foreach($disciplinas as $d)
                    <option value="{{ $d->id }}" {{ $request->disciplina_id == $d->id ? 'selected' : '' }}>{{ $d->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Data</label>
                <input type="date" name="data" value="{{ $request->data }}" class="w-full border rounded-lg px-3 py-2 text-sm" required>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-magnifying-glass mr-1"></i> Carregar</button>
            </div>
        </form>
    </div>

    {{-- Chamada --}}
    @if($roster)
        @if($roster['matriculas']->isEmpty())
        <div class="bg-white rounded-xl border p-8 text-center text-gray-400">Nenhum aluno matriculado nesta turma montada.</div>
        @else
        <form method="POST" action="{{ route('academico.frequencia.salvar') }}" class="bg-white rounded-xl border overflow-hidden">
            @csrf
            <input type="hidden" name="turma_montada_id" value="{{ $request->turma_montada_id }}">
            <input type="hidden" name="disciplina_id" value="{{ $request->disciplina_id }}">
            <input type="hidden" name="data" value="{{ $request->data }}">

            <div class="px-5 py-3 border-b flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-700">Chamada — {{ \Carbon\Carbon::parse($request->data)->format('d/m/Y') }}</h2>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700"><i class="fa-solid fa-floppy-disk mr-1"></i> Salvar Frequência</button>
            </div>

            <div class="p-4 border-b">
                <label class="block text-xs font-medium text-gray-600 mb-1">Conteúdo Ministrado</label>
                <textarea name="conteudo_ministrado" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $roster['conteudo'] }}</textarea>
            </div>

            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Aluno</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Situação</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($roster['matriculas'] as $m)
                    @php $st = $roster['registros']->get($m->id)?->status ?? 'presente'; @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 font-medium text-gray-800">{{ $m->aluno?->pessoa?->nome ?? 'Matrícula '.$m->id }}</td>
                        <td class="px-4 py-2">
                            <div class="flex gap-4 justify-center text-sm">
                                <label class="flex items-center gap-1 cursor-pointer"><input type="radio" name="status[{{ $m->id }}]" value="presente" {{ $st === 'presente' ? 'checked' : '' }} class="text-green-600"> <span class="text-green-700">Presente</span></label>
                                <label class="flex items-center gap-1 cursor-pointer"><input type="radio" name="status[{{ $m->id }}]" value="ausente" {{ $st === 'ausente' ? 'checked' : '' }} class="text-red-600"> <span class="text-red-700">Ausente</span></label>
                                <label class="flex items-center gap-1 cursor-pointer"><input type="radio" name="status[{{ $m->id }}]" value="justificada" {{ $st === 'justificada' ? 'checked' : '' }} class="text-amber-600"> <span class="text-amber-700">Justificada</span></label>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
        @endif
    @else
    <div class="bg-white rounded-xl border p-8 text-center text-gray-400">Selecione turma, disciplina e data para registrar a frequência.</div>
    @endif
</div>
@endsection
