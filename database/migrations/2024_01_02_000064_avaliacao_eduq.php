<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabela de Avaliação no modelo EDUQ ao vivo: cada avaliação tem SIGLA + Nota Máxima
 * + "Avaliação obrigatória?", e a média do boletim vem da avaliação da Fórmula
 * (ex.: (P1+P2)/2) sobre as siglas. Escolha do cliente (2026-07-11).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tabela_avaliacao_itens', function (Blueprint $table) {
            if (!Schema::hasColumn('tabela_avaliacao_itens', 'sigla')) {
                $table->string('sigla', 20)->nullable();
            }
            if (!Schema::hasColumn('tabela_avaliacao_itens', 'nota_maxima')) {
                $table->decimal('nota_maxima', 5, 2)->default(10);
            }
            if (!Schema::hasColumn('tabela_avaliacao_itens', 'obrigatoria')) {
                $table->boolean('obrigatoria')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('tabela_avaliacao_itens', function (Blueprint $table) {
            $table->dropColumn(['sigla', 'nota_maxima', 'obrigatoria']);
        });
    }
};
