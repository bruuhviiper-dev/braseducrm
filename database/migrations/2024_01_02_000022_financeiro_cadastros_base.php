<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('bancos')) {
            Schema::create('bancos', function (Blueprint $table) {
                $table->id();
                $table->string('codigo')->nullable(); // código FEBRABAN (ex.: 001, 341)
                $table->string('nome');
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('centros_custo')) {
            Schema::create('centros_custo', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->string('codigo')->nullable();
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('formas_pagamento')) {
            Schema::create('formas_pagamento', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->string('tipo')->nullable(); // dinheiro, cartao_credito, cartao_debito, boleto, pix, cheque, transferencia
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('motivos_devolucao_cheque')) {
            Schema::create('motivos_devolucao_cheque', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('motivos_devolucao_cheque');
        Schema::dropIfExists('formas_pagamento');
        Schema::dropIfExists('centros_custo');
        Schema::dropIfExists('bancos');
    }
};
