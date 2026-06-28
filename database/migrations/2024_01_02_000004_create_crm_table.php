<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('origens_interessado', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('categorias_interessado', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('interessados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->nullable()->constrained('pessoas')->nullOnDelete();
            $table->string('nome');
            $table->string('email')->nullable();
            $table->string('telefone', 20)->nullable();
            $table->string('celular', 20)->nullable();
            $table->foreignId('origem_id')->nullable()->constrained('origens_interessado')->nullOnDelete();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias_interessado')->nullOnDelete();
            $table->foreignId('curso_id')->nullable()->constrained('cursos')->nullOnDelete();
            $table->text('observacoes')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('motivos_perda', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('motivos_ganho', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('motivos_pausa', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('tags_crm', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cor', 7)->default('#3B82F6');
            $table->timestamps();
        });

        Schema::create('eventos_crm', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('icone')->nullable();
            $table->string('cor', 7)->default('#3B82F6');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('funis', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->boolean('padrao')->default(false);
            $table->boolean('ativo')->default(true);
            $table->json('configuracoes')->nullable();
            $table->timestamps();
        });

        Schema::create('etapas_funil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('funil_id')->constrained('funis')->cascadeOnDelete();
            $table->string('nome');
            $table->integer('ordem');
            $table->string('cor', 7)->default('#3B82F6');
            $table->integer('prazo_dias')->nullable();
            $table->timestamps();
        });

        Schema::create('produtos_servicos_crm', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->decimal('valor', 15, 2)->nullable();
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('oportunidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interessado_id')->constrained('interessados');
            $table->foreignId('funil_id')->constrained('funis');
            $table->foreignId('etapa_funil_id')->constrained('etapas_funil');
            $table->foreignId('consultor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('curso_id')->nullable()->constrained('cursos')->nullOnDelete();
            $table->foreignId('produto_servico_id')->nullable()->constrained('produtos_servicos_crm')->nullOnDelete();
            $table->string('titulo')->nullable();
            $table->decimal('valor', 15, 2)->nullable();
            $table->enum('situacao', ['aberta', 'ganha', 'perdida', 'pausada'])->default('aberta');
            $table->foreignId('motivo_perda_id')->nullable()->constrained('motivos_perda')->nullOnDelete();
            $table->foreignId('motivo_ganho_id')->nullable()->constrained('motivos_ganho')->nullOnDelete();
            $table->foreignId('motivo_pausa_id')->nullable()->constrained('motivos_pausa')->nullOnDelete();
            $table->date('data_previsao_fechamento')->nullable();
            $table->date('data_fechamento')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        Schema::create('oportunidade_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oportunidade_id')->constrained('oportunidades')->cascadeOnDelete();
            $table->foreignId('tag_crm_id')->constrained('tags_crm')->cascadeOnDelete();
        });

        Schema::create('atividades_oportunidade', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oportunidade_id')->constrained('oportunidades')->cascadeOnDelete();
            $table->foreignId('evento_crm_id')->nullable()->constrained('eventos_crm')->nullOnDelete();
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->datetime('data_agendamento')->nullable();
            $table->datetime('data_conclusao')->nullable();
            $table->enum('situacao', ['pendente', 'concluida', 'cancelada'])->default('pendente');
            $table->timestamps();
        });

        Schema::create('metas_crm', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('funil_id')->nullable()->constrained('funis')->nullOnDelete();
            $table->foreignId('consultor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('tipo', ['quantidade', 'valor'])->default('quantidade');
            $table->enum('periodo', ['semanal', 'mensal'])->default('mensal');
            $table->decimal('meta_valor', 15, 2);
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->timestamps();
        });

        Schema::create('propostas_crm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oportunidade_id')->constrained('oportunidades')->cascadeOnDelete();
            $table->string('titulo');
            $table->decimal('valor', 15, 2);
            $table->text('descricao')->nullable();
            $table->enum('situacao', ['rascunho', 'enviada', 'aceita', 'recusada'])->default('rascunho');
            $table->date('data_envio')->nullable();
            $table->date('validade')->nullable();
            $table->timestamps();
        });

        Schema::create('configuracoes_crm', function (Blueprint $table) {
            $table->id();
            $table->boolean('roleta_ativa')->default(false);
            $table->integer('dias_perda_automatica')->nullable();
            $table->string('rd_station_token')->nullable();
            $table->string('rd_station_url')->nullable();
            $table->json('configuracoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracoes_crm');
        Schema::dropIfExists('propostas_crm');
        Schema::dropIfExists('metas_crm');
        Schema::dropIfExists('atividades_oportunidade');
        Schema::dropIfExists('oportunidade_tags');
        Schema::dropIfExists('oportunidades');
        Schema::dropIfExists('produtos_servicos_crm');
        Schema::dropIfExists('etapas_funil');
        Schema::dropIfExists('funis');
        Schema::dropIfExists('eventos_crm');
        Schema::dropIfExists('tags_crm');
        Schema::dropIfExists('motivos_pausa');
        Schema::dropIfExists('motivos_ganho');
        Schema::dropIfExists('motivos_perda');
        Schema::dropIfExists('interessados');
        Schema::dropIfExists('categorias_interessado');
        Schema::dropIfExists('origens_interessado');
    }
};
