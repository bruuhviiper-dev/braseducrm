<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lote de funcionalidades do inventário real do EDUQ:
 * 222 Cálculo de Comissões (comissão editável por matrícula), 217/243/261/49/264 cadastros,
 * 240 Eventos do Portal, 92 Config Portal de Inscrição, 230 Link de Pagamento Avulso.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 222: comissão editável POR MATRÍCULA (vendedor fixo + % livre, base = taxa de matrícula)
        Schema::table('matriculas', function (Blueprint $table) {
            $table->foreignId('consultor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('comissao_percentual', 5, 2)->nullable();
        });

        // 230: link de pagamento avulso (token público)
        Schema::table('titulos_receber', function (Blueprint $table) {
            $table->string('token_pagamento', 40)->nullable()->index();
        });

        // 217 Agrupador de Títulos
        Schema::create('agrupadores_titulo', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        // 243 Grupo de Categorias (A Pagar)
        Schema::create('grupos_categoria_pagar', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        // 261 Motivo de Restrição
        Schema::create('motivos_restricao', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        // 49 Cadastro de Modelo de Papel
        Schema::create('modelos_papel', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('tamanho')->nullable(); // A4, Carta...
            $table->text('descricao')->nullable();
            $table->timestamps();
        });

        // 264 Motivo de Finalização de Atividade (CRM)
        Schema::create('motivos_finalizacao_atividade', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        // 240 Cadastro de Eventos (Portal Aluno)
        Schema::create('eventos_portal_aluno', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->date('data_inicio')->nullable();
            $table->date('data_fim')->nullable();
            $table->boolean('publicado')->default(true);
            $table->timestamps();
        });

        // 92 Configuração (Portal de Inscrição) — singleton
        Schema::create('configuracoes_portal_inscricao', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->nullable();
            $table->string('cor_primaria', 20)->nullable();
            $table->text('texto_boas_vindas')->nullable();
            $table->boolean('exigir_cpf')->default(true);
            $table->boolean('permitir_cupom')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $table->dropColumn(['consultor_id', 'comissao_percentual']);
        });
        Schema::table('titulos_receber', function (Blueprint $table) {
            $table->dropColumn('token_pagamento');
        });
        Schema::dropIfExists('agrupadores_titulo');
        Schema::dropIfExists('grupos_categoria_pagar');
        Schema::dropIfExists('motivos_restricao');
        Schema::dropIfExists('modelos_papel');
        Schema::dropIfExists('motivos_finalizacao_atividade');
        Schema::dropIfExists('eventos_portal_aluno');
        Schema::dropIfExists('configuracoes_portal_inscricao');
    }
};
