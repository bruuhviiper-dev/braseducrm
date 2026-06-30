@extends('layouts.app')
@section('title', 'Entrega de Documentos')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">19</span>
                <div>
                    <h1 class="text-base font-semibold text-gray-800">{{ $matricula->aluno?->pessoa?->nome ?? 'Aluno' }}</h1>
                    <p class="text-xs text-gray-500">Matrícula {{ $matricula->numero_matricula ?? $matricula->id }} — {{ $matricula->turma?->curso?->nome ?? '—' }}</p>
                </div>
            </div>
            <a href="{{ route('academico.entregas-documento.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>

        <form action="{{ route('academico.entregas-documento.salvar', $matricula) }}" method="POST" class="p-6">
            @csrf @method('PUT')
            <table class="w-full text-sm">
                <thead class="text-xs text-gray-500 uppercase border-b">
                    <tr>
                        <th class="text-left pb-2">Entregue</th>
                        <th class="text-left pb-2">Documento</th>
                        <th class="text-left pb-2">Data de Entrega</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($documentos as $doc)
                    @php $e = $entregas->get($doc->id); @endphp
                    <tr>
                        <td class="py-2">
                            <input type="checkbox" name="entregue[{{ $doc->id }}]" value="1" {{ $e?->entregue ? 'checked' : '' }} class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        </td>
                        <td class="py-2 text-gray-800">
                            {{ $doc->nome }}
                            @if($doc->obrigatorio)<span class="text-[10px] bg-red-100 text-red-700 px-1.5 rounded-full ml-1">obrigatório</span>@endif
                        </td>
                        <td class="py-2">
                            <input type="date" name="data_entrega[{{ $doc->id }}]" value="{{ optional($e?->data_entrega)->format('Y-m-d') }}" class="border rounded px-2 py-1 text-sm">
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="py-6 text-center text-gray-400">Nenhum documento cadastrado para este curso.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="flex justify-end gap-3 pt-4 mt-4 border-t">
                <a href="{{ route('academico.entregas-documento.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar Entregas</button>
            </div>
        </form>
    </div>
</div>
@endsection
