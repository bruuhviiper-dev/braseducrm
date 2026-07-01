<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('atributos_adicionais')) {
            Schema::create('atributos_adicionais', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->string('entidade')->default('pessoa'); // pessoa | aluno | matricula | curso
                $table->string('tipo')->default('texto'); // texto | numero | data | booleano | lista
                $table->boolean('obrigatorio')->default(false);
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('assinaturas')) {
            Schema::create('assinaturas', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->string('cargo')->nullable();
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('cabecalhos')) {
            Schema::create('cabecalhos', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->longText('conteudo')->nullable();
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }

        // campanhas_indicacao e indicacoes já existem (migration 000005) — reutilizadas.
    }

    public function down(): void
    {
        Schema::dropIfExists('cabecalhos');
        Schema::dropIfExists('assinaturas');
        Schema::dropIfExists('atributos_adicionais');
    }
};
