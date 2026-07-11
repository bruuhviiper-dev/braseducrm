<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Campos do Passo 1 do wizard "Matrícula e Histórico" (doc CRM): Tag opcional e solução personalizada (SOLPER). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $table->string('tag')->nullable();
            $table->string('solucao_personalizada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $table->dropColumn(['tag', 'solucao_personalizada']);
        });
    }
};
