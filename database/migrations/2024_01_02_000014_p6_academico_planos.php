<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cadastros simples do Academico
        if (!Schema::hasTable('motivos_cancelamento_matricula')) {
            Schema::create('motivos_cancelamento_matricula', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tags_matricula')) {
            Schema::create('tags_matricula', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tags_turma_montada')) {
            Schema::create('tags_turma_montada', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }

        // Plano de Ensino/Aula (203, 204, 119)
        if (!Schema::hasTable('topicos_plano')) {
            Schema::create('topicos_plano', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->boolean('obrigatoria')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('estruturas_plano')) {
            Schema::create('estruturas_plano', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('estrutura_plano_topico')) {
            Schema::create('estrutura_plano_topico', function (Blueprint $table) {
                $table->id();
                $table->foreignId('estrutura_plano_id')->constrained('estruturas_plano')->cascadeOnDelete();
                $table->foreignId('topico_plano_id')->constrained('topicos_plano')->cascadeOnDelete();
                $table->integer('ordem')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('planos_ensino')) {
            Schema::create('planos_ensino', function (Blueprint $table) {
                $table->id();
                $table->foreignId('turma_montada_id')->constrained('turmas_montadas')->cascadeOnDelete();
                $table->foreignId('disciplina_id')->constrained('disciplinas')->cascadeOnDelete();
                $table->foreignId('estrutura_plano_id')->nullable()->constrained('estruturas_plano')->nullOnDelete();
                $table->timestamps();
                $table->unique(['turma_montada_id', 'disciplina_id']);
            });
        }

        if (!Schema::hasTable('plano_ensino_conteudos')) {
            Schema::create('plano_ensino_conteudos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('plano_ensino_id')->constrained('planos_ensino')->cascadeOnDelete();
                $table->foreignId('topico_plano_id')->constrained('topicos_plano')->cascadeOnDelete();
                $table->text('conteudo')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('plano_ensino_conteudos');
        Schema::dropIfExists('planos_ensino');
        Schema::dropIfExists('estrutura_plano_topico');
        Schema::dropIfExists('estruturas_plano');
        Schema::dropIfExists('topicos_plano');
        Schema::dropIfExists('tags_turma_montada');
        Schema::dropIfExists('tags_matricula');
        Schema::dropIfExists('motivos_cancelamento_matricula');
    }
};
