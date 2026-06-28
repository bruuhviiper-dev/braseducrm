@extends('layouts.app')
@section('title', isset($matricula) ? 'Editar Matricula' : 'Nova Matricula')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl border p-6">
        <h1 class="text-lg font-semibold text-gray-800 mb-6">{{ isset($matricula) ? 'Editar Matricula' : 'Nova Matricula' }}</h1>

        <form action="{{ isset($matricula) ? route('academico.matriculas.update', $matricula) : route('academico.matriculas.store') }}" method="POST">
            @csrf
            @if(isset($matricula))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Aluno --}}
                <div class="md:col-span-2">
                    <label for="aluno_id" class="block text-sm font-medium text-gray-700 mb-1">Aluno <span class="text-red-500">*</span></label>
                    <select name="aluno_id" id="aluno_id" required
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('aluno_id') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        @foreach($alunos as $aluno)
                            <option value="{{ $aluno->id }}" {{ old('aluno_id', $matricula->aluno_id ?? '') == $aluno->id ? 'selected' : '' }}>
                                {{ $aluno->pessoa->nome ?? 'Aluno #'.$aluno->id }}
                            </option>
                        @endforeach
                    </select>
                    @error('aluno_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Turma --}}
                <div>
                    <label for="turma_id" class="block text-sm font-medium text-gray-700 mb-1">Turma <span class="text-red-500">*</span></label>
                    <select name="turma_id" id="turma_id" required
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('turma_id') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        @foreach($turmas as $turma)
                            <option value="{{ $turma->id }}" {{ old('turma_id', $matricula->turma_id ?? '') == $turma->id ? 'selected' : '' }}>
                                {{ $turma->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('turma_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Data Matricula --}}
                <div>
                    <label for="data_matricula" class="block text-sm font-medium text-gray-700 mb-1">Data da Matricula <span class="text-red-500">*</span></label>
                    <input type="date" name="data_matricula" id="data_matricula" value="{{ old('data_matricula', isset($matricula) && $matricula->data_matricula ? $matricula->data_matricula->format('Y-m-d') : '') }}" required
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('data_matricula') border-red-500 @enderror">
                    @error('data_matricula')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Situacao --}}
                <div>
                    <label for="situacao" class="block text-sm font-medium text-gray-700 mb-1">Situacao <span class="text-red-500">*</span></label>
                    <select name="situacao" id="situacao" required
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('situacao') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        <option value="ativa" {{ old('situacao', $matricula->situacao ?? '') === 'ativa' ? 'selected' : '' }}>Ativa</option>
                        <option value="em_andamento" {{ old('situacao', $matricula->situacao ?? '') === 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                        <option value="trancada" {{ old('situacao', $matricula->situacao ?? '') === 'trancada' ? 'selected' : '' }}>Trancada</option>
                        <option value="cancelada" {{ old('situacao', $matricula->situacao ?? '') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        <option value="concluida" {{ old('situacao', $matricula->situacao ?? '') === 'concluida' ? 'selected' : '' }}>Concluida</option>
                    </select>
                    @error('situacao')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Forma de Ingresso --}}
                <div>
                    <label for="forma_ingresso_id" class="block text-sm font-medium text-gray-700 mb-1">Forma de Ingresso</label>
                    <select name="forma_ingresso_id" id="forma_ingresso_id"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('forma_ingresso_id') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        @foreach($formasIngresso as $forma)
                            <option value="{{ $forma->id }}" {{ old('forma_ingresso_id', $matricula->forma_ingresso_id ?? '') == $forma->id ? 'selected' : '' }}>
                                {{ $forma->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('forma_ingresso_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Observacoes --}}
                <div class="md:col-span-2">
                    <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-1">Observacoes</label>
                    <textarea name="observacoes" id="observacoes" rows="3"
                              class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('observacoes') border-red-500 @enderror">{{ old('observacoes', $matricula->observacoes ?? '') }}</textarea>
                    @error('observacoes')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t">
                <a href="{{ route('academico.matriculas.index') }}" class="px-4 py-2 border rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700 transition">
                    <i class="fa-solid fa-check mr-1"></i> Salvar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
