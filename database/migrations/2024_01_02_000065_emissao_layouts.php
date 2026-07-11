<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Layouts salvos das emissões (EDUQ: dropdown "Layouts" nos construtores de relatório).
 * Cada layout guarda as colunas escolhidas (ordem) e os filtros, por usuário e por função (código da emissão).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emissao_layouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('funcao_codigo'); // ex.: 79
            $table->string('nome');
            $table->json('colunas');           // lista ordenada de chaves de coluna
            $table->json('filtros')->nullable(); // filtros salvos
            $table->boolean('padrao')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emissao_layouts');
    }
};
