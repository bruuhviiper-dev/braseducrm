@extends('layouts.app')
@section('title', 'Calendario')

@section('content')
<div class="max-w-5xl mx-auto" x-data="calendario({{ $mes }}, {{ $ano }}, {{ $atividades->toJson() }})">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-calendar text-primary-500 text-xl"></i>
            <h1 class="text-2xl font-bold text-gray-800">Calendario</h1>
        </div>
        <div class="flex items-center gap-2">
            <a :href="prevUrl" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
            <span class="text-lg font-semibold text-gray-800 min-w-[180px] text-center" x-text="mesNome + ' ' + ano"></span>
            <a :href="nextUrl" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden">
        {{-- Header --}}
        <div class="grid grid-cols-7 bg-gray-50 border-b">
            <template x-for="dia in diasSemana" :key="dia">
                <div class="px-2 py-3 text-center text-xs font-semibold text-gray-500 uppercase" x-text="dia"></div>
            </template>
        </div>

        {{-- Calendar Grid --}}
        <div class="grid grid-cols-7">
            <template x-for="(semana, si) in semanas" :key="si">
                <template x-for="(dia, di) in semana" :key="si + '-' + di">
                    <div class="min-h-[100px] border-b border-r p-1.5 relative"
                         :class="{
                            'bg-gray-50': !dia.mesAtual,
                            'bg-blue-50': dia.hoje
                         }">
                        <span class="text-sm font-medium inline-flex items-center justify-center w-7 h-7 rounded-full"
                              :class="{
                                'text-gray-400': !dia.mesAtual,
                                'text-gray-700': dia.mesAtual && !dia.hoje,
                                'bg-primary-500 text-white': dia.hoje
                              }"
                              x-text="dia.numero"></span>
                        <div class="mt-1 space-y-0.5">
                            <template x-for="ativ in dia.atividades" :key="ativ.id">
                                <div class="text-xs px-1.5 py-0.5 rounded truncate cursor-default"
                                     :class="{
                                        'bg-red-100 text-red-700': ativ.situacao === 'atrasada',
                                        'bg-yellow-100 text-yellow-700': ativ.situacao === 'pendente',
                                        'bg-green-100 text-green-700': ativ.situacao === 'concluida'
                                     }"
                                     :title="ativ.titulo"
                                     x-text="ativ.titulo"></div>
                            </template>
                        </div>
                    </div>
                </template>
            </template>
        </div>
    </div>

    {{-- Legend --}}
    <div class="flex items-center gap-4 mt-4 justify-center">
        <div class="flex items-center gap-1.5 text-xs text-gray-600">
            <span class="w-3 h-3 bg-red-100 border border-red-200 rounded"></span> Atrasada
        </div>
        <div class="flex items-center gap-1.5 text-xs text-gray-600">
            <span class="w-3 h-3 bg-yellow-100 border border-yellow-200 rounded"></span> Pendente
        </div>
        <div class="flex items-center gap-1.5 text-xs text-gray-600">
            <span class="w-3 h-3 bg-green-100 border border-green-200 rounded"></span> Concluida
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function calendario(mes, ano, atividades) {
    return {
        mes: mes,
        ano: ano,
        atividades: atividades,
        diasSemana: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        meses: ['Janeiro', 'Fevereiro', 'Marco', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],

        get mesNome() {
            return this.meses[this.mes - 1];
        },

        get prevUrl() {
            let m = this.mes - 1;
            let a = this.ano;
            if (m < 1) { m = 12; a--; }
            return '/calendario?mes=' + m + '&ano=' + a;
        },

        get nextUrl() {
            let m = this.mes + 1;
            let a = this.ano;
            if (m > 12) { m = 1; a++; }
            return '/calendario?mes=' + m + '&ano=' + a;
        },

        get semanas() {
            const primeiroDia = new Date(this.ano, this.mes - 1, 1);
            const ultimoDia = new Date(this.ano, this.mes, 0);
            const diasNoMes = ultimoDia.getDate();
            const diaInicioSemana = primeiroDia.getDay();
            const hoje = new Date();

            let semanas = [];
            let semana = [];

            // Previous month days
            const diasMesAnterior = new Date(this.ano, this.mes - 1, 0).getDate();
            for (let i = diaInicioSemana - 1; i >= 0; i--) {
                semana.push({
                    numero: diasMesAnterior - i,
                    mesAtual: false,
                    hoje: false,
                    atividades: []
                });
            }

            // Current month days
            for (let d = 1; d <= diasNoMes; d++) {
                const isHoje = hoje.getDate() === d && hoje.getMonth() === this.mes - 1 && hoje.getFullYear() === this.ano;
                const diaStr = this.ano + '-' + String(this.mes).padStart(2, '0') + '-' + String(d).padStart(2, '0');
                const ativsDia = this.atividades.filter(a => {
                    if (!a.data_vencimento) return false;
                    return a.data_vencimento.substring(0, 10) === diaStr;
                });

                semana.push({
                    numero: d,
                    mesAtual: true,
                    hoje: isHoje,
                    atividades: ativsDia
                });

                if (semana.length === 7) {
                    semanas.push(semana);
                    semana = [];
                }
            }

            // Next month days
            if (semana.length > 0) {
                let nextDay = 1;
                while (semana.length < 7) {
                    semana.push({
                        numero: nextDay++,
                        mesAtual: false,
                        hoje: false,
                        atividades: []
                    });
                }
                semanas.push(semana);
            }

            return semanas;
        }
    };
}
</script>
@endpush
