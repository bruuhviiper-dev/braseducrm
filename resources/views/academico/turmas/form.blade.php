@extends('layouts.app')
@section('title', isset($turma) ? 'Editar Turma' : 'Nova Turma')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl border p-6">
        <h1 class="text-lg font-semibold text-gray-800 mb-6">{{ isset($turma) ? 'Editar Turma' : 'Nova Turma' }}</h1>

        <form action="{{ isset($turma) ? route('academico.turmas.update', $turma) : route('academico.turmas.store') }}" method="POST">
            @csrf
            @if(isset($turma))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Nome --}}
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" id="nome" value="{{ old('nome', $turma->nome ?? '') }}" required
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('nome') border-red-500 @enderror">
                    @error('nome')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Codigo --}}
                <div>
                    <label for="codigo" class="block text-sm font-medium text-gray-700 mb-1">Codigo <span class="text-red-500">*</span></label>
                    <input type="text" name="codigo" id="codigo" value="{{ old('codigo', $turma->codigo ?? '') }}" required
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('codigo') border-red-500 @enderror">
                    @error('codigo')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Curso --}}
                <div>
                    <label for="curso_id" class="block text-sm font-medium text-gray-700 mb-1">Curso <span class="text-red-500">*</span></label>
                    <select name="curso_id" id="curso_id" required
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('curso_id') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}" {{ old('curso_id', $turma->curso_id ?? '') == $curso->id ? 'selected' : '' }}>
                                {{ $curso->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('curso_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Matriz Curricular --}}
                <div>
                    <label for="matriz_curricular_id" class="block text-sm font-medium text-gray-700 mb-1">Matriz Curricular</label>
                    <select name="matriz_curricular_id" id="matriz_curricular_id"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('matriz_curricular_id') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        @foreach($matrizes as $matriz)
                            <option value="{{ $matriz->id }}" {{ old('matriz_curricular_id', $turma->matriz_curricular_id ?? '') == $matriz->id ? 'selected' : '' }}>
                                {{ $matriz->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('matriz_curricular_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Turno --}}
                <div>
                    <label for="turno_id" class="block text-sm font-medium text-gray-700 mb-1">Turno</label>
                    <select name="turno_id" id="turno_id"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('turno_id') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        @foreach($turnos as $turno)
                            <option value="{{ $turno->id }}" {{ old('turno_id', $turma->turno_id ?? '') == $turno->id ? 'selected' : '' }}>
                                {{ $turno->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('turno_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Periodo Letivo --}}
                <div>
                    <label for="periodo_letivo_id" class="block text-sm font-medium text-gray-700 mb-1">Periodo Letivo</label>
                    <select name="periodo_letivo_id" id="periodo_letivo_id"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('periodo_letivo_id') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        @foreach($periodos as $periodo)
                            <option value="{{ $periodo->id }}" {{ old('periodo_letivo_id', $turma->periodo_letivo_id ?? '') == $periodo->id ? 'selected' : '' }}>
                                {{ $periodo->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('periodo_letivo_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Data Inicio --}}
                <div>
                    <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-1">Data Inicio</label>
                    <input type="date" name="data_inicio" id="data_inicio" value="{{ old('data_inicio', isset($turma) && $turma->data_inicio ? $turma->data_inicio->format('Y-m-d') : '') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('data_inicio') border-red-500 @enderror">
                    @error('data_inicio')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Data Fim --}}
                <div>
                    <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-1">Data Fim</label>
                    <input type="date" name="data_fim" id="data_fim" value="{{ old('data_fim', isset($turma) && $turma->data_fim ? $turma->data_fim->format('Y-m-d') : '') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('data_fim') border-red-500 @enderror">
                    @error('data_fim')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Vagas --}}
                <div>
                    <label for="vagas" class="block text-sm font-medium text-gray-700 mb-1">Vagas</label>
                    <input type="number" name="vagas" id="vagas" value="{{ old('vagas', $turma->vagas ?? '') }}" min="0"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('vagas') border-red-500 @enderror">
                    @error('vagas')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Situacao --}}
                <div>
                    <label for="situacao" class="block text-sm font-medium text-gray-700 mb-1">Situacao <span class="text-red-500">*</span></label>
                    <select name="situacao" id="situacao" required
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('situacao') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        <option value="aberta" {{ old('situacao', $turma->situacao ?? '') === 'aberta' ? 'selected' : '' }}>Aberta</option>
                        <option value="em_andamento" {{ old('situacao', $turma->situacao ?? '') === 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                        <option value="encerrada" {{ old('situacao', $turma->situacao ?? '') === 'encerrada' ? 'selected' : '' }}>Encerrada</option>
                        <option value="cancelada" {{ old('situacao', $turma->situacao ?? '') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                    @error('situacao')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t">
                <a href="{{ route('academico.turmas.index') }}" class="px-4 py-2 border rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
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
