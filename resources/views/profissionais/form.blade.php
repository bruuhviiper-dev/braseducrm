@extends('layouts.app')
@section('title', 'Cadastro de Profissional')

@section('content')
<div class="w-full"
     x-data="{
        aba: 'dados',
        infoAdd: {{ json_encode(old('informacoes_adicionais', $profissional->informacoes_adicionais ?? '')) }},
        infoCur: {{ json_encode(old('informacoes_curriculares', $profissional->informacoes_curriculares ?? '')) }}
     }">
    <div class="bg-white">
        <div class="px-5 py-3 border-b flex items-center gap-2">
            <span class="text-sm font-semibold text-gray-400">12</span>
            <h1 class="text-lg font-bold text-gray-800">Cadastro de Profissional</h1>
        </div>
        <div class="px-5 pt-3 border-b flex gap-5">
            <button type="button" @click="aba = 'dados'" :class="aba === 'dados' ? 'text-cyan-600 border-cyan-500' : 'text-gray-500 border-transparent'" class="pb-2 text-sm font-semibold border-b-2">Dados Básicos <span class="text-red-500">*</span></button>
            <button type="button" @click="aba = 'horario'" :class="aba === 'horario' ? 'text-cyan-600 border-cyan-500' : 'text-gray-500 border-transparent'" class="pb-2 text-sm font-semibold border-b-2">Horário de Trabalho</button>
        </div>
        <form method="POST" enctype="multipart/form-data" action="{{ isset($profissional) ? route('profissionais.update', $profissional) : route('profissionais.store') }}" class="p-5 space-y-4">
            @csrf
            @if(isset($profissional)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div x-show="aba === 'dados'" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Admissão</label>
                        <input type="date" name="data_admissao" value="{{ old('data_admissao', isset($profissional) && $profissional->data_admissao ? \Illuminate\Support\Carbon::parse($profissional->data_admissao)->format('Y-m-d') : '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Demissão</label>
                        <input type="date" name="data_demissao" value="{{ old('data_demissao', isset($profissional) && $profissional->data_demissao ? \Illuminate\Support\Carbon::parse($profissional->data_demissao)->format('Y-m-d') : '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pessoa <span class="text-red-500">*</span></label>
                    <select name="pessoa_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
                        <option value="">Selecione...</option>
                        @foreach($pessoas as $p)
                        <option value="{{ $p->id }}" {{ old('pessoa_id', $profissional->pessoa_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Profissional</label>
                    <select name="tipo_profissional_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <option value="">Selecione...</option>
                        @foreach($tipos as $t)
                        <option value="{{ $t->id }}" {{ old('tipo_profissional_id', $profissional->tipo_profissional_id ?? '') == $t->id ? 'selected' : '' }}>{{ $t->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Titularidade</label>
                    <select name="titularidade_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <option value="">Selecione...</option>
                        @foreach($titularidades as $t)
                        <option value="{{ $t->id }}" {{ old('titularidade_id', $profissional->titularidade_id ?? '') == $t->id ? 'selected' : '' }}>{{ $t->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Informações Adicionais</label>
                    <textarea name="informacoes_adicionais" rows="3" maxlength="2000" x-model="infoAdd" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400"></textarea>
                    <p class="text-xs text-gray-400 text-right mt-0.5"><span x-text="(infoAdd || '').length"></span> / 2000</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Assinatura digitalizada</label>
                    <label class="inline-flex items-center gap-2 px-4 py-2 border rounded-lg text-sm text-gray-600 cursor-pointer hover:bg-gray-50">
                        <i class="fa-solid fa-upload"></i> Carregar
                        <input type="file" name="assinatura" accept="image/*" class="hidden" onchange="this.parentElement.querySelector('span')?.remove(); this.parentElement.insertAdjacentHTML('beforeend', '<span class=&quot;text-xs text-cyan-600&quot;>' + this.files[0].name + '</span>')">
                    </label>
                    @if(!empty($profissional->assinatura_path ?? null))
                    <p class="text-xs text-gray-400 mt-1">Arquivo atual: {{ basename($profissional->assinatura_path) }}</p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cargo</label>
                    <input type="text" name="cargo" value="{{ old('cargo', $profissional->cargo ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Informações curriculares</label>
                    <textarea name="informacoes_curriculares" rows="5" maxlength="20000" x-model="infoCur" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400"></textarea>
                    <p class="text-xs text-gray-400 text-right mt-0.5"><span x-text="(infoCur || '').length"></span> / 20000</p>
                </div>

                <input type="hidden" name="ativo" value="1">
            </div>

            <div x-show="aba === 'horario'" x-cloak class="space-y-3">
                <p class="text-sm text-gray-500">Defina os horários de trabalho do profissional por dia da semana.</p>
                @foreach(['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'] as $dia)
                <div class="flex items-center gap-3">
                    <span class="w-24 text-sm text-gray-600">{{ $dia }}</span>
                    <input type="time" name="horario[{{ Str::slug($dia) }}][inicio]" class="border rounded-lg px-3 py-1.5 text-sm">
                    <span class="text-xs text-gray-400">às</span>
                    <input type="time" name="horario[{{ Str::slug($dia) }}][fim]" class="border rounded-lg px-3 py-1.5 text-sm">
                </div>
                @endforeach
            </div>

            <div class="flex justify-end pt-3 sticky bottom-4 z-10">
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30">Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
