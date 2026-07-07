<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Régua de cobrança (59): avisos de vencimento/atraso/baixa — todo dia às 8h
Artisan::command('eduq:reguas', function () {
    $r = \App\Http\Controllers\Financeiro\ConfiguracaoFinanceiroController::executarReguas();
    $this->info("Régua de cobrança: {$r['enviadas']} enviada(s), {$r['puladas']} ignorada(s).");
})->purpose('Processa a régua de cobrança (avisos de vencimento, atraso e pagamento)');

// Roleta anti-estagnação do CRM: redistribui leads parados além do tempo máximo
Artisan::command('eduq:roleta', function () {
    $movidas = \App\Http\Controllers\Crm\ConfiguracaoCrmController::executarRedistribuicao();
    $this->info($movidas === null ? 'Roleta pulada (fim de semana).' : "Roleta: {$movidas} lead(s) redistribuído(s).");
})->purpose('Redistribui pela roleta os leads estagnados do CRM');

// Perda automática: oportunidades sem movimentação além do prazo viram perdidas
Artisan::command('eduq:perda-automatica', function () {
    $n = \App\Http\Controllers\Crm\ConfiguracaoCrmController::executarPerdaAutomatica();
    $this->info("Perda automática: {$n} oportunidade(s) marcada(s) como perdida(s).");
})->purpose('Marca como perdidas as oportunidades inativas além do prazo configurado');

Schedule::command('eduq:reguas')->dailyAt('08:00');
Schedule::command('eduq:roleta')->everyThirtyMinutes();
Schedule::command('eduq:perda-automatica')->dailyAt('03:00');
