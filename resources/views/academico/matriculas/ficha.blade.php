@extends('layouts.app')
@section('title', 'Matrícula e Histórico')

@php
$abas = ['Dados Básicos', 'Enturmações', 'Notas e Faltas', 'Calendário', 'Financeiro', 'Contratos e Declarações', 'Documentos', 'Requerimentos', 'Atendimentos', 'Assinatura Eletrônica', 'Horas Complementares', 'Enade', 'Histórico de Movimentações', 'Informações de Saúde'];
$corStatus = fn ($v) => in_array($v, ['Confirmada', 'Assinado', 'Em dia', 'Sem restrição', 'Completo']) ? 'green' : (in_array($v, ['Enviado', 'Pendente e Enviado']) ? 'orange' : 'red');
$diasSemana = [1 => 'Segunda', 2 => 'Terça', 3 => 'Quarta', 4 => 'Quinta', 5 => 'Sexta', 6 => 'Sábado', 7 => 'Domingo'];
@endphp

@section('content')
<div class="w-full" x-data="{ aba: 'Dados Básicos' }">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('academico.matriculas.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
        <div>
            <h1 class="text-xl font-bold text-gray-800">Matrícula e Histórico</h1>
            <p class="text-xs text-gray-400">Acadêmico › Matrícula</p>
        </div>
    </div>

    @if(session('success'))<div class="mb-3 bg-green-50 border border-green-200 text-green-700 px-4 py-2.5 rounded text-sm">{{ session('success') }}</div>@endif
    @if($errors->any())
    <div class="mb-3 bg-red-50 border border-red-200 text-red-700 px-4 py-2.5 rounded text-sm"><ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    {{-- Barra de abas (EDUQ: underline ciano na ativa) --}}
    <div class="border-b bg-white sticky top-14 z-20 overflow-x-auto">
        <div class="flex whitespace-nowrap">
            @foreach($abas as $a)
            <button type="button" @click="aba = '{{ $a }}'"
                    :class="aba === '{{ $a }}' ? 'border-cyan-500 text-gray-900 bg-gray-50 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="px-5 py-3 text-sm border-b-2 transition-colors">{{ $a }}</button>
            @endforeach
        </div>
    </div>

    {{-- Cards de status --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 my-4">
        @foreach(['MATRÍCULA' => $status['matricula'], 'CONTRATO' => $status['contrato'], '$ FINANCEIRO' => $status['financeiro'], 'RESTRIÇÃO' => $status['restricao'], 'DOCUMENTOS' => $status['documentos']] as $rotulo => $valor)
        @php $c = $corStatus($valor); @endphp
        <div class="bg-white border rounded-lg overflow-hidden">
            <div class="h-1 {{ $c === 'green' ? 'bg-green-500' : ($c === 'orange' ? 'bg-orange-400' : 'bg-red-500') }}"></div>
            <div class="px-4 py-3">
                <p class="text-[10px] font-semibold text-gray-400 tracking-wide">{{ $rotulo }}</p>
                <p class="text-sm font-bold {{ $c === 'green' ? 'text-green-600' : ($c === 'orange' ? 'text-orange-500' : 'text-red-600') }}">{{ $valor }}</p>
            </div>
        </div>
        @endforeach
    </div>

    @if($oportunidadeOrigem)
    <a href="{{ route('crm.oportunidades.index') }}" class="inline-flex items-center gap-2 px-3 py-2 border border-orange-300 text-orange-600 rounded-lg text-xs font-semibold hover:bg-orange-50 mb-4">
        <i class="fa-solid fa-bullseye"></i> Ver oportunidade que gerou essa matrícula (#{{ $oportunidadeOrigem->id }})
    </a>
    @endif

    {{-- ==================== DADOS BÁSICOS ==================== --}}
    <div x-show="aba === 'Dados Básicos'" x-cloak>
        <form method="POST" action="{{ route('academico.matriculas.ficha.salvar', $matricula) }}" id="form-dados">
            @csrf @method('PUT')
            <div class="flex gap-5">
                <div class="w-32 shrink-0">
                    <div class="w-32 h-36 rounded-lg bg-gray-100 border flex items-center justify-center overflow-hidden">
                        @if($pessoa?->foto)<img src="{{ asset('storage/'.$pessoa->foto) }}" class="object-cover w-full h-full" alt="">
                        @else<i class="fa-solid fa-user text-4xl text-gray-300"></i>@endif
                    </div>
                </div>
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Identificador</label>
                        <input type="text" value="{{ $matricula->numero_matricula ?? '#'.$matricula->id }}" class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-500" readonly>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Aluno <span class="text-red-500">*</span></label>
                        <input type="text" value="{{ $matricula->aluno_id }} - {{ $pessoa?->nome }}" class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-500" readonly>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Turma montada atual <span class="text-red-500">*</span></label>
                        <select name="turma_montada_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach($turmasMontadas as $tm)
                            <option value="{{ $tm->id }}" @selected(old('turma_montada_id', $matricula->turma_montada_id) == $tm->id)>{{ $tm->sigla ?? $tm->nome ?? 'Turma '.$tm->id }} - {{ $tm->turma?->nome }} {{ $tm->situacao === 'finalizada' ? '(Finalizada)' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Matriz Curricular <span class="text-red-500">*</span></label>
                        <select name="matriz_curricular_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach($matrizes as $mz)
                            <option value="{{ $mz->id }}" @selected(old('matriz_curricular_id', $matricula->matriz_curricular_id ?? $matricula->turma?->matriz_curricular_id) == $mz->id)>{{ $mz->sigla ?? '' }} {{ $mz->sigla ? '- ' : '' }}{{ $mz->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Data de criação</label>
                        <input type="date" name="data_matricula" value="{{ old('data_matricula', $matricula->data_matricula?->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Previsão de Conclusão</label>
                        <input type="date" name="previsao_conclusao" value="{{ old('previsao_conclusao', $matricula->previsao_conclusao?->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Dia de início das aulas</label>
                        <input type="date" name="data_inicio_aulas" value="{{ old('data_inicio_aulas', $matricula->data_inicio_aulas?->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Forma de Ingresso <span class="text-red-500">*</span></label>
                        <select name="forma_ingresso_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach($formasIngresso as $fi)
                            <option value="{{ $fi->id }}" @selected(old('forma_ingresso_id', $matricula->forma_ingresso_id) == $fi->id)>{{ $fi->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Como conheceu o curso?</label>
                        <input type="text" name="como_conheceu" value="{{ old('como_conheceu', $matricula->como_conheceu) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Operador <span class="text-red-500">*</span></label>
                        <select name="consultor_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach($operadores as $op)
                            <option value="{{ $op->id }}" @selected(old('consultor_id', $matricula->consultor_id) == $op->id)>{{ $op->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Responsável Financeiro</label>
                        <select name="responsavel_financeiro_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach($pessoas as $ps)
                            <option value="{{ $ps->id }}" @selected(old('responsavel_financeiro_id', $matricula->responsavel_financeiro_id) == $ps->id)>{{ $ps->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- ==================== ENTURMAÇÕES ==================== --}}
    <div x-show="aba === 'Enturmações'" x-cloak class="space-y-4">
        <div class="bg-gray-100 rounded-t-lg px-4 py-2.5 text-sm font-semibold text-gray-700"><i class="fa-regular fa-rectangle-list mr-2 text-gray-400"></i>Disciplinas e equivalências</div>
        <div class="flex flex-wrap gap-3" x-data="{ acao: '' }">
            <button type="button" @click="acao = acao === 'transferir' ? '' : 'transferir'" class="px-4 py-2 border border-cyan-300 text-cyan-600 rounded-lg text-sm font-semibold hover:bg-cyan-50">Transferir de turma <i class="fa-solid fa-right-left ml-1"></i></button>
            <button type="button" @click="acao = acao === 'equivalente' ? '' : 'equivalente'" class="px-4 py-2 border border-cyan-300 text-cyan-600 rounded-lg text-sm font-semibold hover:bg-cyan-50">Adicionar disciplina equivalente <i class="fa-solid fa-plus ml-1"></i></button>
            <button type="button" @click="acao = acao === 'optativa' ? '' : 'optativa'" class="px-4 py-2 border border-cyan-300 text-cyan-600 rounded-lg text-sm font-semibold hover:bg-cyan-50">Adicionar disciplina optativa <i class="fa-solid fa-plus ml-1"></i></button>
            <button type="button" @click="acao = acao === 'normal' ? '' : 'normal'" class="px-4 py-2 border border-cyan-300 text-cyan-600 rounded-lg text-sm font-semibold hover:bg-cyan-50">Adicionar disciplina <i class="fa-solid fa-plus ml-1"></i></button>

            <form x-show="acao === 'transferir'" x-cloak method="POST" action="{{ route('academico.matriculas.ficha.transferir', $matricula) }}" class="w-full flex items-center gap-2 border rounded-lg p-3 bg-gray-50">
                @csrf
                <select name="turma_montada_id" class="flex-1 border rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Nova turma montada...</option>
                    @foreach($turmasMontadas as $tm)<option value="{{ $tm->id }}">{{ $tm->sigla ?? $tm->nome ?? 'Turma '.$tm->id }} - {{ $tm->turma?->nome }}</option>@endforeach
                </select>
                <button class="px-4 py-2 bg-cyan-500 text-white rounded-lg text-sm font-semibold hover:bg-cyan-400" onclick="return confirm('Transferir o aluno de turma? As enturmações passam para a nova turma.')">Transferir</button>
            </form>
            <template x-for="tipoAcao in ['normal','equivalente','optativa']" :key="tipoAcao">
                <form x-show="acao === tipoAcao" x-cloak method="POST" action="{{ route('academico.matriculas.ficha.enturmar', $matricula) }}" class="w-full flex items-center gap-2 border rounded-lg p-3 bg-gray-50">
                    @csrf
                    <input type="hidden" name="tipo" :value="tipoAcao">
                    <select name="disciplina_id" class="flex-1 border rounded-lg px-3 py-2 text-sm" required>
                        <option value="">Disciplina...</option>
                        @foreach($disciplinas as $d)<option value="{{ $d->id }}">{{ $d->sigla ? $d->sigla.' - ' : '' }}{{ $d->nome }}</option>@endforeach
                    </select>
                    <input type="date" name="data_inicio" value="{{ now()->format('Y-m-d') }}" class="border rounded-lg px-3 py-2 text-sm">
                    <button class="px-4 py-2 bg-cyan-500 text-white rounded-lg text-sm font-semibold hover:bg-cyan-400">Enturmar</button>
                </form>
            </template>
        </div>

        <div>
            <div class="bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 flex items-center justify-between rounded-t-lg">
                <span><i class="fa-solid fa-plus mr-2 text-gray-400"></i>Disciplinas enturmadas</span>
                <span class="text-xs font-normal text-gray-400">{{ $matricula->enturmacoes->count() }} itens</span>
            </div>
            <table class="w-full text-sm text-left border">
                <thead class="bg-gray-50 border-b"><tr>
                    <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Disciplina</th>
                    <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Turma Montada</th>
                    <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Início</th>
                    <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                    <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase text-right">Ações</th>
                </tr></thead>
                <tbody class="divide-y">
                    @forelse($matricula->enturmacoes as $ent)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2.5 text-gray-800">{{ $ent->disciplina?->sigla ? $ent->disciplina->sigla.' - ' : '' }}{{ $ent->disciplina?->nome }}</td>
                        <td class="px-4 py-2.5 text-gray-600">{{ $ent->turmaMontada?->sigla ?? $ent->turmaMontada?->nome ?? '—' }}</td>
                        <td class="px-4 py-2.5 text-gray-600">{{ $ent->data_inicio?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-4 py-2.5 text-gray-600">{{ \App\Models\Enturmacao::TIPOS[$ent->tipo] ?? $ent->tipo }}</td>
                        <td class="px-4 py-2.5 text-right">
                            <form method="POST" action="{{ route('academico.matriculas.ficha.desenturmar', [$matricula, $ent]) }}" class="inline" onsubmit="return confirm('Remover essa enturmação?')">
                                @csrf @method('DELETE')
                                <button class="p-1.5 border border-red-200 text-red-500 rounded hover:bg-red-50"><i class="fa-regular fa-trash-can text-xs"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Nenhuma disciplina enturmada. As notas usam as disciplinas do horário da turma quando não há enturmação manual.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ==================== NOTAS E FALTAS ==================== --}}
    <div x-show="aba === 'Notas e Faltas'" x-cloak class="space-y-4">
        <div>
            <div class="bg-gray-100 rounded-t-lg px-4 py-2.5 text-sm font-semibold text-gray-700">Histórico Escolar</div>
            <div class="border border-t-0 rounded-b-lg p-4 space-y-3">
                <a href="{{ route('academico.matriculas.historico', $matricula) }}" class="block w-full text-center px-4 py-2.5 border rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50"><i class="fa-regular fa-clipboard mr-2"></i>Ver histórico escolar</a>
                <label class="flex items-start gap-3">
                    <input type="checkbox" name="exibir_historico_prioritario" value="1" form="form-dados" {{ $matricula->exibir_historico_prioritario ? 'checked' : '' }} class="mt-0.5 rounded border-gray-300 text-cyan-500">
                    <span>
                        <span class="block text-sm font-semibold text-gray-700">Exibir histórico escolar prioritariamente</span>
                        <span class="block text-xs text-red-400"><i class="fa-solid fa-circle-minus mr-1"></i>O Histórico Escolar aparecerá antes das notas e faltas no módulo acadêmico do portal do aluno</span>
                    </span>
                </label>
            </div>
        </div>
        <div>
            <div class="bg-gray-100 rounded-t-lg px-4 py-2.5 text-sm font-semibold text-gray-700"><i class="fa-regular fa-file-lines mr-2 text-gray-400"></i>Progressos e Notas Parciais</div>
            <div class="border border-t-0 rounded-b-lg p-4 space-y-3">
                <p class="text-sm font-semibold text-cyan-600 border-b-2 border-cyan-500 inline-block pb-1">{{ $matricula->turma?->curso?->nome ?? 'Curso' }} (Atual)</p>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Turma Montada</label>
                    <input type="text" value="{{ $matricula->turmaMontada?->sigla ?? '' }} - {{ $matricula->turmaMontada?->turma?->nome ?? $matricula->turmaMontada?->nome ?? '—' }} {{ $matricula->turmaMontada?->situacao === 'finalizada' ? '(Finalizada)' : '' }}" class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-600" readonly>
                </div>
                <a href="{{ route('academico.lancamento-notas.index') }}" class="block w-full text-center px-4 py-2.5 border rounded-lg text-sm font-semibold text-cyan-600 hover:bg-cyan-50">Editar Notas e Faltas</a>
                <table class="w-full text-sm">
                    <thead class="bg-gray-100"><tr>
                        <th class="px-4 py-2.5 text-left text-sm font-semibold text-gray-700">Disciplina</th>
                        <th class="px-4 py-2.5 text-center text-sm font-semibold text-gray-700">Carga Horária</th>
                        <th class="px-4 py-2.5 text-center text-sm font-semibold text-gray-700">Média</th>
                        <th class="px-4 py-2.5 text-center text-sm font-semibold text-gray-700">Falta(s)</th>
                        <th class="px-4 py-2.5 text-center text-sm font-semibold text-gray-700">Frequência</th>
                        <th class="px-4 py-2.5 text-center text-sm font-semibold text-gray-700">Resultado</th>
                    </tr></thead>
                    <tbody class="divide-y">
                        @forelse($disciplinasNotas as $dn)
                        <tr>
                            <td class="px-4 py-2.5 text-gray-800">({{ $matricula->data_inicio_aulas?->format('d/m/Y') ?? $matricula->data_matricula?->format('d/m/Y') }}) {{ $dn['disciplina']->nome }}</td>
                            <td class="px-4 py-2.5 text-center text-gray-600">{{ $dn['disciplina']->carga_horaria ?? '—' }}</td>
                            <td class="px-4 py-2.5 text-center text-cyan-600 underline">{{ $dn['media'] !== null ? number_format($dn['media'], 2, ',', '.') : '-' }}</td>
                            <td class="px-4 py-2.5 text-center text-cyan-600">{{ $dn['faltas'] ?? '-' }}</td>
                            <td class="px-4 py-2.5 text-center text-cyan-600 underline">{{ $dn['frequencia'] !== null ? number_format($dn['frequencia'], 2, ',', '.').' %' : '-' }}</td>
                            <td class="px-4 py-2.5 text-center">
                                @php $ok = $dn['resultado'] === 'Aprovado'; $cursando = $dn['resultado'] === 'Cursando'; @endphp
                                <span class="text-xs font-semibold text-white px-2 py-1 rounded {{ $ok ? 'bg-green-500' : ($cursando ? 'bg-blue-400' : 'bg-red-500') }}">{{ $dn['resultado'] }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">Nenhuma disciplina com lançamentos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ==================== CALENDÁRIO ==================== --}}
    <div x-show="aba === 'Calendário'" x-cloak class="space-y-3">
        <a href="{{ route('academico.emissoes.index') }}" class="inline-block px-4 py-2 border border-cyan-300 text-cyan-600 rounded-lg text-sm font-semibold hover:bg-cyan-50"><i class="fa-regular fa-calendar mr-1"></i> Emitir horário semanal</a>
        <table class="w-full text-sm text-left border">
            <thead class="bg-gray-50 border-b"><tr>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Dia</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Horário</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Disciplina</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Sala</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($horarios as $h)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2.5 text-gray-800">{{ $diasSemana[$h->dia_semana] ?? $h->dia_semana }}</td>
                    <td class="px-4 py-2.5 text-gray-600">{{ substr($h->hora_inicio, 0, 5) }} — {{ substr($h->hora_fim, 0, 5) }}</td>
                    <td class="px-4 py-2.5 text-gray-600">{{ $h->disciplina?->nome ?? '—' }}</td>
                    <td class="px-4 py-2.5 text-gray-600">{{ $h->sala?->nome ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400">Sem horários cadastrados para a turma montada atual.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ==================== FINANCEIRO ==================== --}}
    <div x-show="aba === 'Financeiro'" x-cloak class="space-y-4">
        <div class="border rounded-lg p-4 bg-gray-50">
            <p class="text-sm font-semibold text-gray-500 mb-2">Responsável financeiro</p>
            @php $rf = $matricula->responsavelFinanceiro ?? $pessoa; @endphp
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-1 text-xs text-gray-600">
                <p><span class="text-gray-400">Nome:</span> {{ $rf?->nome ?? '—' }}</p>
                <p><span class="text-gray-400">CPF:</span> {{ $rf?->cpf ?? '—' }}</p>
                <p><span class="text-gray-400">Nascimento:</span> {{ $rf?->data_nascimento ? \Carbon\Carbon::parse($rf->data_nascimento)->format('d/m/Y') : '—' }}</p>
                <p><span class="text-gray-400">Endereço:</span> {{ $rf?->endereco ?? '—' }}{{ $rf?->numero ? ', '.$rf->numero : '' }}</p>
                <p><span class="text-gray-400">Bairro:</span> {{ $rf?->bairro ?? '—' }}</p>
                <p><span class="text-gray-400">Cidade:</span> {{ $rf?->cidade ?? '—' }}{{ $rf?->uf ? ' - '.$rf->uf : '' }}</p>
                <p><span class="text-gray-400">Celular:</span> {{ $rf?->celular ?? '—' }}</p>
                <p><span class="text-gray-400">E-mail:</span> {{ $rf?->email ?? '—' }}</p>
                <p><span class="text-gray-400">CEP:</span> {{ $rf?->cep ?? '—' }}</p>
            </div>
        </div>

        <p class="text-sm font-semibold text-cyan-600 border-b-2 border-cyan-500 inline-block pb-1">Contas a receber ({{ $titulos->count() }})</p>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            @foreach([
                ['TOTAL PAGO', $fin['pago'], 'green'],
                ['TOTAL ABERTO', $fin['aberto'], 'gray'],
                ['TOTAL VENCIDO', $fin['vencido'], 'red'],
                ['JUROS (ABERTO)', $fin['juros'], 'gray'],
                ['MULTA (ABERTO)', $fin['multa'], 'gray'],
            ] as [$rot, $val, $cor])
            <div class="bg-white border rounded-lg overflow-hidden">
                <div class="h-1 {{ $cor === 'green' ? 'bg-green-500' : ($cor === 'red' ? 'bg-red-500' : 'bg-gray-300') }}"></div>
                <div class="px-3 py-2.5">
                    <p class="text-[10px] font-semibold text-gray-400">$ {{ $rot }}</p>
                    <p class="text-sm font-bold {{ $cor === 'green' ? 'text-green-600' : ($cor === 'red' ? 'text-red-600' : 'text-gray-700') }}">R$ {{ number_format((float) $val, 2, ',', '.') }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center">
            <a href="{{ Route::has('financeiro.emissoes.resumo-pessoa') ? route('financeiro.emissoes.resumo-pessoa', ['pessoa_id' => $pessoa?->id]) : route('financeiro.emissoes.index') }}" class="inline-block px-4 py-2 border border-cyan-300 text-cyan-600 rounded-lg text-sm font-semibold hover:bg-cyan-50"><i class="fa-regular fa-file-lines mr-1"></i> Emitir resumo financeiro</a>
        </div>

        <div class="border rounded-lg divide-y">
            @forelse($titulos as $t)
            <div class="flex items-start gap-3 p-3 hover:bg-gray-50">
                <div class="w-1 self-stretch rounded {{ $t->situacao === 'pago' ? 'bg-green-500' : ($t->data_vencimento?->isPast() && in_array($t->situacao, ['aberto','vencido']) ? 'bg-red-500' : 'bg-gray-300') }}"></div>
                <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-2 text-xs">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $pessoa?->nome }}</p>
                        <p class="text-gray-500">Plano de Conta: {{ $t->categoriaReceber?->nome ?? 'Matrícula' }}</p>
                        @if($t->situacao === 'pago')<p class="text-green-600 font-medium">Recebido em {{ $t->data_pagamento?->format('d/m/Y') }} via {{ ucfirst($t->forma_pagamento ?? 'manual') }}</p>@endif
                    </div>
                    <div>
                        <p class="text-gray-800 font-medium">{{ $t->data_vencimento?->format('d/m/Y') }}</p>
                        <p class="text-gray-500">{{ ucfirst($t->situacao) }}{{ $t->observacoes ? ' · '.Str::limit($t->observacoes, 40) : '' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-500">Nº: {{ $t->numero_documento ?? $t->id }}</p>
                        <p class="text-gray-800">Valor: <span class="font-semibold {{ $t->situacao === 'pago' ? 'text-green-600' : 'text-red-600' }}">R$ {{ number_format((float) $t->valor_original, 2, ',', '.') }}</span></p>
                        @if($t->situacao === 'pago')<p class="text-gray-400">Valor pago R$ {{ number_format((float) ($t->valor_pago ?: $t->valor_original), 2, ',', '.') }}</p>@endif
                    </div>
                </div>
            </div>
            @empty
            <p class="p-6 text-center text-gray-400 text-sm">Nenhuma fatura para esta matrícula.</p>
            @endforelse
        </div>
    </div>

    {{-- ==================== CONTRATOS E DECLARAÇÕES ==================== --}}
    <div x-show="aba === 'Contratos e Declarações'" x-cloak class="space-y-3">
        <p class="text-xs text-gray-400">Selecione o modelo e gere o documento preenchido com os dados desta matrícula.</p>
        <table class="w-full text-sm text-left border">
            <thead class="bg-gray-50 border-b"><tr>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Modelo</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase text-right">Gerar</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($modelosDocumento as $md)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2.5 text-gray-800">{{ $md->nome }}</td>
                    <td class="px-4 py-2.5 text-gray-600">{{ \App\Models\ModeloDocumento::TIPOS[$md->tipo] ?? $md->tipo }}</td>
                    <td class="px-4 py-2.5 text-right">
                        <a href="{{ Route::has('emissoes.declaracao-matricula') ? route('emissoes.declaracao-matricula', $matricula->aluno_id) : '#' }}" target="_blank" class="px-3 py-1.5 border border-cyan-300 text-cyan-600 rounded-lg text-xs font-semibold hover:bg-cyan-50">Gerar Documento PDF</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-4 py-6 text-center text-gray-400">Nenhum modelo cadastrado (Geral › Modelos de Documento).</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ==================== DOCUMENTOS ==================== --}}
    <div x-show="aba === 'Documentos'" x-cloak class="space-y-4">
        <div class="flex flex-wrap gap-3">
            <a href="{{ Route::has('academico.entregas-documento.index') ? route('academico.entregas-documento.index') : '#' }}" class="px-4 py-2 border border-cyan-300 text-cyan-600 rounded-lg text-sm font-semibold hover:bg-cyan-50">Receber documentos</a>
            <form method="POST" action="{{ route('academico.matriculas.ficha.aprovar-documentos', $matricula) }}" class="inline">@csrf
                <button class="px-4 py-2 border border-cyan-300 text-cyan-600 rounded-lg text-sm font-semibold hover:bg-cyan-50">Aprovar documentos</button>
            </form>
            <a href="{{ Route::has('academico.emissoes.index') ? route('academico.emissoes.index') : '#' }}" class="px-4 py-2 border rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50">Emitir declaração de entrega</a>
        </div>

        <div class="border rounded-lg p-4">
            <p class="text-sm font-bold text-gray-700 mb-3"><i class="fa-solid fa-check text-green-500 mr-2"></i>DOCUMENTOS ENTREGUES ({{ $entregues->count() }})</p>
            <div class="space-y-2">
                @foreach($entregues as $ed)
                <div class="border rounded-lg px-3 py-2.5 text-sm font-semibold text-gray-800 border-l-4 border-l-green-500">{{ $ed->documento?->nome }} ({{ $ed->data_entrega?->format('d/m/Y') }}){{ $ed->aprovado ? '' : ' — aguardando aprovação' }}</div>
                @endforeach
            </div>
        </div>
        <div class="border rounded-lg p-4">
            <p class="text-sm font-bold text-gray-700"><i class="fa-solid fa-circle-check text-orange-400 mr-2"></i>DOCUMENTOS PENDENTES DE APROVAÇÃO ({{ $pendentesAprovacao->count() }})</p>
            <div class="space-y-2 mt-3">
                @foreach($pendentesAprovacao as $ed)
                <div class="border rounded-lg px-3 py-2.5 text-sm font-semibold text-gray-800 border-l-4 border-l-orange-400">{{ $ed->documento?->nome }}</div>
                @endforeach
            </div>
        </div>
        <div class="border rounded-lg p-4">
            <p class="text-sm font-bold text-gray-700 mb-3"><i class="fa-solid fa-ban text-red-500 mr-2"></i>DOCUMENTOS PENDENTES ({{ $docsPendentes->count() }})</p>
            <div class="space-y-2">
                @foreach($docsPendentes as $doc)
                <div class="border rounded-lg px-3 py-2.5 text-sm font-semibold text-gray-800 border-l-4 border-l-red-500">{{ $doc->nome }}</div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ==================== REQUERIMENTOS ==================== --}}
    <div x-show="aba === 'Requerimentos'" x-cloak class="space-y-3">
        <a href="{{ route('requerimentos.create') }}" class="inline-block px-4 py-2 border border-cyan-300 text-cyan-600 rounded-lg text-sm font-semibold hover:bg-cyan-50"><i class="fa-solid fa-plus mr-1"></i> Novo requerimento</a>
        <table class="w-full text-sm text-left border">
            <thead class="bg-gray-50 border-b"><tr>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Descrição</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Solicitação</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Situação</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($requerimentos as $req)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2.5 text-gray-800">{{ $req->tipoRequerimento?->nome ?? Str::limit($req->descricao, 60) }}</td>
                    <td class="px-4 py-2.5 text-gray-600">{{ $req->created_at?->format('d/m/Y') }}</td>
                    <td class="px-4 py-2.5 text-gray-600">{{ ucfirst(str_replace('_', ' ', $req->situacao)) }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-4 py-6 text-center text-gray-400">Nenhum requerimento desta matrícula.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ==================== ATENDIMENTOS ==================== --}}
    <div x-show="aba === 'Atendimentos'" x-cloak class="space-y-3">
        <a href="{{ route('atendimentos.create') }}" class="inline-block px-4 py-2 border border-cyan-300 text-cyan-600 rounded-lg text-sm font-semibold hover:bg-cyan-50"><i class="fa-solid fa-plus mr-1"></i> Novo atendimento</a>
        <table class="w-full text-sm text-left border">
            <thead class="bg-gray-50 border-b"><tr>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Data</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Categoria</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Descrição</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Situação</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase text-right">Detalhes</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($atendimentos as $at)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2.5 text-gray-600">{{ $at->created_at?->format('d/m/Y') }}</td>
                    <td class="px-4 py-2.5 text-gray-600">{{ $at->categoria?->nome ?? '—' }}</td>
                    <td class="px-4 py-2.5 text-gray-800">{{ Str::limit($at->descricao, 60) }}</td>
                    <td class="px-4 py-2.5 text-gray-600">{{ ucfirst(str_replace('_', ' ', $at->situacao)) }}</td>
                    <td class="px-4 py-2.5 text-right"><a href="{{ route('atendimentos.edit', $at) }}" class="text-cyan-600 text-xs font-semibold hover:underline">Detalhes</a></td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Nenhum atendimento desta pessoa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ==================== ASSINATURA ELETRÔNICA ==================== --}}
    <div x-show="aba === 'Assinatura Eletrônica'" x-cloak class="space-y-4">
        <div class="flex flex-wrap gap-3">
            <form method="POST" action="{{ route('academico.matriculas.ficha.assinatura', $matricula) }}" class="flex items-center gap-2">
                @csrf
                <input type="text" name="documento" placeholder="Nome do documento (ex.: Contrato de prestação de serviços)" class="w-80 border rounded-lg px-3 py-2 text-sm" required>
                <button class="px-4 py-2 border border-cyan-300 text-cyan-600 rounded-lg text-sm font-semibold hover:bg-cyan-50">Selecionar o documento e enviar</button>
                <button name="ja_assinado" value="1" class="px-4 py-2 border rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50">Adicionar documento já assinado</button>
            </form>
        </div>
        <table class="w-full text-sm text-left border">
            <thead class="bg-gray-50 border-b"><tr>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Documento</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Situação</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Link para assinatura</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase text-right">Ações</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($matricula->assinaturasEletronicas as $as)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2.5 text-gray-800">{{ $as->documento }}</td>
                    <td class="px-4 py-2.5"><span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $as->situacao === 'assinado' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-600' }}">{{ ucfirst($as->situacao) }}</span></td>
                    <td class="px-4 py-2.5 text-xs text-gray-500">
                        @if($as->token)
                        <span class="font-mono">{{ url('/assinar/'.$as->token) }}</span>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ url('/assinar/'.$as->token) }}'); this.innerText='Copiado!'" class="ml-1 text-cyan-600 font-semibold hover:underline">Copiar mensagem</button>
                        @else — @endif
                    </td>
                    <td class="px-4 py-2.5 text-right">
                        <form method="POST" action="{{ route('academico.matriculas.ficha.assinatura.remover', [$matricula, $as]) }}" class="inline" onsubmit="return confirm('Remover este documento?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 border border-red-200 text-red-500 rounded hover:bg-red-50"><i class="fa-regular fa-trash-can text-xs"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400">Nenhum documento enviado para assinatura eletrônica.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ==================== HORAS COMPLEMENTARES ==================== --}}
    <div x-show="aba === 'Horas Complementares'" x-cloak class="space-y-3">
        <a href="{{ Route::has('academico.horas-complementares.create') ? route('academico.horas-complementares.create') : route('academico.horas-complementares.index') }}" class="inline-block px-4 py-2 border border-cyan-300 text-cyan-600 rounded-lg text-sm font-semibold hover:bg-cyan-50"><i class="fa-solid fa-plus mr-1"></i> Novo Lançamento</a>
        <table class="w-full text-sm text-left border">
            <thead class="bg-gray-50 border-b"><tr>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Descrição</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase text-center">Horas</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Situação</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($matricula->horasComplementares as $hc)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2.5 text-gray-800">{{ $hc->tipo }}</td>
                    <td class="px-4 py-2.5 text-gray-600">{{ Str::limit($hc->descricao, 60) }}</td>
                    <td class="px-4 py-2.5 text-center text-gray-600">{{ number_format((float) $hc->quantidade, 1, ',', '.') }}</td>
                    <td class="px-4 py-2.5 text-gray-600">{{ $hc->situacao }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400">Nenhum lançamento de horas complementares.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ==================== ENADE ==================== --}}
    <div x-show="aba === 'Enade'" x-cloak class="space-y-3">
        <form method="POST" action="{{ route('academico.matriculas.ficha.enade', $matricula) }}" class="flex items-center gap-2">
            @csrf
            <input type="text" name="edicao" placeholder="Edição (ex.: 2026)" class="w-32 border rounded-lg px-3 py-2 text-sm" required>
            <select name="situacao" class="border rounded-lg px-3 py-2 text-sm" required>
                @foreach(\App\Models\EnadeRegistro::SITUACOES as $k => $v)<option value="{{ $k }}">{{ $v }}</option>@endforeach
            </select>
            <input type="text" name="observacao" placeholder="Observação" class="flex-1 border rounded-lg px-3 py-2 text-sm">
            <button class="px-4 py-2 border border-cyan-300 text-cyan-600 rounded-lg text-sm font-semibold hover:bg-cyan-50"><i class="fa-solid fa-plus mr-1"></i> Adicionar edição</button>
        </form>
        <table class="w-full text-sm text-left border">
            <thead class="bg-gray-50 border-b"><tr>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Edição</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Situação</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Observação</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase text-right">Ações</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($matricula->enades as $en)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2.5 text-gray-800">{{ $en->edicao }}</td>
                    <td class="px-4 py-2.5 text-gray-600">{{ \App\Models\EnadeRegistro::SITUACOES[$en->situacao] ?? $en->situacao }}</td>
                    <td class="px-4 py-2.5 text-gray-600">{{ $en->observacao ?? '—' }}</td>
                    <td class="px-4 py-2.5 text-right">
                        <form method="POST" action="{{ route('academico.matriculas.ficha.enade.remover', [$matricula, $en]) }}" class="inline" onsubmit="return confirm('Remover esta edição?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 border border-red-200 text-red-500 rounded hover:bg-red-50"><i class="fa-regular fa-trash-can text-xs"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400">Nenhuma edição do Enade registrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ==================== HISTÓRICO DE MOVIMENTAÇÕES ==================== --}}
    <div x-show="aba === 'Histórico de Movimentações'" x-cloak>
        <table class="w-full text-sm text-left border">
            <thead class="bg-gray-50 border-b"><tr>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Data</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Operador</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Descrição</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Situação</th>
                <th class="px-4 py-2.5 text-xs font-medium text-gray-500 uppercase">Tag</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($matricula->movimentacoes as $mv)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2.5 text-gray-600">{{ $mv->created_at?->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-2.5 text-gray-600">{{ $mv->user?->nome ?? 'Sistema' }}</td>
                    <td class="px-4 py-2.5 text-gray-800">{{ $mv->descricao }}</td>
                    <td class="px-4 py-2.5 text-gray-600">{{ $mv->situacao ? ucfirst(str_replace('_',' ', $mv->situacao)) : '—' }}</td>
                    <td class="px-4 py-2.5 text-gray-600">{{ $mv->tag ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Nenhuma movimentação registrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ==================== INFORMAÇÕES DE SAÚDE ==================== --}}
    <div x-show="aba === 'Informações de Saúde'" x-cloak>
        <label class="block text-xs font-medium text-gray-500 mb-1">Observações de saúde do aluno (alergias, medicamentos, necessidades especiais)</label>
        <textarea name="observacoes_saude" form="form-dados" rows="6" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('observacoes_saude', $pessoa?->observacoes_saude) }}</textarea>
    </div>

    {{-- Salvar (pílula flutuante, EDUQ) --}}
    <div class="flex justify-end pt-4 sticky bottom-4 z-10">
        <button type="submit" form="form-dados" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30"><i class="fa-solid fa-check mr-1"></i>Salvar</button>
    </div>
</div>
@endsection
