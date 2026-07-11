<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Docs "eduq documentos" (revisão de estrutura + catálogo de permissões):
 * permissões por função+ação com defaults por departamento e extras por usuário,
 * SOLPER (soluções personalizadas), ficha do título 64, cadastro de pessoa 11
 * (documentos civis, contas/PIX, anexos), escola, grade de horário e horas
 * complementares com fluxo de aprovação.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Permissões (catálogo em config/catalogo_permissoes.php). Regra do doc:
        // salvo por DEPARTAMENTO + liberações extras por usuário; admin tem tudo.
        if (!Schema::hasTable('permissoes_departamento'))
        Schema::create('permissoes_departamento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departamento_id')->constrained('departamentos')->cascadeOnDelete();
            $table->unsignedInteger('funcao_codigo');
            $table->string('acao', 120); // Adicionar|Editar|...|_ocultar_menu
            $table->timestamps();
            $table->unique(['departamento_id', 'funcao_codigo', 'acao'], 'perm_dep_unico');
        });

        if (!Schema::hasTable('permissoes_usuario_extra'))
        Schema::create('permissoes_usuario_extra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('funcao_codigo');
            $table->string('acao', 120);
            $table->timestamps();
            $table->unique(['user_id', 'funcao_codigo', 'acao'], 'perm_usr_unico');
        });

        // 167 aba Soluções Personalizadas (SOLPER): chave/valor lidos pelo motor
        if (!Schema::hasTable('solucoes_personalizadas'))
        Schema::create('solucoes_personalizadas', function (Blueprint $table) {
            $table->id();
            $table->string('chave')->unique();
            $table->string('valor');
            $table->timestamps();
        });

        // Ficha do Título 64: aba Anotações
        if (!Schema::hasTable('anotacoes_titulo'))
        Schema::create('anotacoes_titulo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('titulo_receber_id')->constrained('titulos_receber')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('texto');
            $table->timestamps();
        });

        if (!Schema::hasColumn('titulos_receber', 'ocultar_portal'))
        Schema::table('titulos_receber', function (Blueprint $table) {
            $table->date('vencimento_original')->nullable(); // auditoria de prorrogação
            $table->unsignedInteger('parcela')->nullable();
            $table->unsignedInteger('total_parcelas')->nullable();
            $table->boolean('ocultar_portal')->default(false);
            $table->boolean('nao_emitir_nf')->default(false);
            $table->boolean('apenas_nfse')->default(false);
            $table->unsignedBigInteger('baixado_por')->nullable(); // responsável pela baixa (alterável)
        });

        // Pessoa 11: documentos civis (certidão, reservista, título de eleitor, RG completo)
        if (!Schema::hasColumn('pessoas', 'reservista'))
        Schema::table('pessoas', function (Blueprint $table) {
            $table->date('rg_data_expedicao')->nullable();
            $table->string('rg_uf', 2)->nullable();
            $table->string('certidao_matricula')->nullable();
            $table->string('certidao_numero')->nullable();
            $table->string('certidao_folha')->nullable();
            $table->string('certidao_livro')->nullable();
            $table->string('reservista')->nullable();
            $table->string('titulo_eleitor')->nullable();
            $table->string('titulo_zona')->nullable();
            $table->string('titulo_municipio')->nullable();
            $table->date('titulo_data_expedicao')->nullable();
            $table->string('forma_pagamento_padrao')->nullable(); // aba Dados p/ Contas a Pagar
            $table->unsignedTinyInteger('dia_pagamento')->nullable();
        });

        // Pessoa 11: aba Contas / PIX (pagar ou reembolsar a pessoa)
        if (!Schema::hasTable('contas_pessoa'))
        Schema::create('contas_pessoa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->constrained('pessoas')->cascadeOnDelete();
            $table->string('tipo', 10)->default('pix'); // pix|bancaria
            $table->string('chave_pix_tipo')->nullable();
            $table->string('chave_pix')->nullable();
            $table->string('banco')->nullable();
            $table->string('agencia')->nullable();
            $table->string('conta')->nullable();
            $table->string('tipo_conta')->nullable();
            $table->boolean('do_titular')->default(true);
            $table->string('nome_titular')->nullable();
            $table->string('cpf_titular')->nullable();
            $table->timestamps();
        });

        // Pessoa 11: aba Anexos (arquivo digitalizado com fluxo de homologação)
        if (!Schema::hasTable('anexos_pessoa'))
        Schema::create('anexos_pessoa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->constrained('pessoas')->cascadeOnDelete();
            $table->string('tipo_documento');
            $table->string('arquivo');
            $table->string('descricao')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('situacao', 15)->default('em_analise'); // em_analise|aprovado|rejeitado
            $table->string('motivo_rejeicao')->nullable();
            $table->timestamps();
        });

        // 8 Escola: celular obrigatório no form
        if (!Schema::hasColumn('escolas', 'celular'))
        Schema::table('escolas', function (Blueprint $table) {
            $table->string('celular')->nullable();
        });

        // 36 Grade de Horário: dias da semana + blocos com tipo Aula/Intervalo
        if (!Schema::hasColumn('grades_horario', 'dias_semana'))
        Schema::table('grades_horario', function (Blueprint $table) {
            $table->string('dias_semana')->nullable(); // csv 0-6 (dom-sáb)
        });
        if (!Schema::hasColumn('grade_horario_aulas', 'tipo'))
        Schema::table('grade_horario_aulas', function (Blueprint $table) {
            $table->string('tipo', 12)->default('aula'); // aula|intervalo (intervalo não cobra presença)
        });

        // 239 Horas Complementares: aprovação/recusa com motivo e reenvio pelo aluno
        if (!Schema::hasColumn('horas_complementares', 'arquivo'))
        Schema::table('horas_complementares', function (Blueprint $table) {
            $table->string('arquivo')->nullable();
            $table->string('motivo_recusa')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissoes_departamento');
        Schema::dropIfExists('permissoes_usuario_extra');
        Schema::dropIfExists('solucoes_personalizadas');
        Schema::dropIfExists('anotacoes_titulo');
        Schema::dropIfExists('contas_pessoa');
        Schema::dropIfExists('anexos_pessoa');
        Schema::table('titulos_receber', function (Blueprint $table) {
            $table->dropColumn(['vencimento_original', 'parcela', 'total_parcelas', 'ocultar_portal', 'nao_emitir_nf', 'apenas_nfse', 'baixado_por']);
        });
        Schema::table('pessoas', function (Blueprint $table) {
            $table->dropColumn(['rg_data_expedicao', 'rg_uf', 'certidao_matricula', 'certidao_numero', 'certidao_folha', 'certidao_livro', 'reservista', 'titulo_eleitor', 'titulo_zona', 'titulo_municipio', 'titulo_data_expedicao', 'forma_pagamento_padrao', 'dia_pagamento']);
        });
        Schema::table('escolas', function (Blueprint $table) {
            $table->dropColumn('celular');
        });
        Schema::table('grades_horario', function (Blueprint $table) {
            $table->dropColumn('dias_semana');
        });
        Schema::table('grade_horario_aulas', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
        Schema::table('horas_complementares', function (Blueprint $table) {
            $table->dropColumn(['arquivo', 'motivo_recusa']);
        });
    }
};
