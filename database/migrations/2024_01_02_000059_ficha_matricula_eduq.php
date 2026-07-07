<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ficha "Matrícula e Histórico" (23) fiel ao EDUQ
        if (!Schema::hasColumn('matriculas', 'previsao_conclusao')) {
            Schema::table('matriculas', function (Blueprint $table) {
                $table->date('previsao_conclusao')->nullable();
                $table->date('data_inicio_aulas')->nullable();
                $table->string('como_conheceu')->nullable();
                $table->foreignId('responsavel_financeiro_id')->nullable()->constrained('pessoas')->nullOnDelete();
                $table->foreignId('matriz_curricular_id')->nullable()->constrained('matrizes_curriculares')->nullOnDelete();
                $table->boolean('exibir_historico_prioritario')->default(false);
            });
        }

        // Aba Enturmações: disciplinas enturmadas por matrícula (normal/equivalente/optativa)
        if (!Schema::hasTable('enturmacoes')) {
            Schema::create('enturmacoes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
                $table->foreignId('disciplina_id')->constrained('disciplinas');
                $table->foreignId('turma_montada_id')->nullable()->constrained('turmas_montadas')->nullOnDelete();
                $table->date('data_inicio')->nullable();
                $table->string('tipo')->default('normal'); // normal|equivalente|optativa
                $table->timestamps();
            });
        }

        // Aba Histórico de Movimentações: log Data | Operador | Descrição | Situação | Tag
        if (!Schema::hasTable('movimentacoes_matricula')) {
            Schema::create('movimentacoes_matricula', function (Blueprint $table) {
                $table->id();
                $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('descricao');
                $table->string('situacao')->nullable();
                $table->string('tag')->nullable();
                $table->timestamps();
            });
        }

        // Aba Enade
        if (!Schema::hasTable('enade_registros')) {
            Schema::create('enade_registros', function (Blueprint $table) {
                $table->id();
                $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
                $table->string('edicao');
                $table->string('situacao')->default('ingressante'); // ingressante|concluinte|dispensado
                $table->string('observacao')->nullable();
                $table->timestamps();
            });
        }

        // Aba Assinatura Eletrônica: documentos enviados p/ assinatura do aluno
        if (!Schema::hasTable('assinaturas_eletronicas')) {
            Schema::create('assinaturas_eletronicas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
                $table->string('documento');
                $table->string('arquivo')->nullable();
                $table->string('situacao')->default('pendente'); // pendente|assinado
                $table->string('token', 40)->nullable()->index();
                $table->timestamps();
            });
        }

        // Aba Documentos: aprovação da secretaria (entregue → pendente de aprovação → aprovado)
        if (!Schema::hasColumn('entregas_documento', 'aprovado')) {
            Schema::table('entregas_documento', function (Blueprint $table) {
                $table->boolean('aprovado')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('assinaturas_eletronicas');
        Schema::dropIfExists('enade_registros');
        Schema::dropIfExists('movimentacoes_matricula');
        Schema::dropIfExists('enturmacoes');
        Schema::table('matriculas', function (Blueprint $table) {
            $table->dropColumn(['previsao_conclusao', 'data_inicio_aulas', 'como_conheceu', 'responsavel_financeiro_id', 'matriz_curricular_id', 'exibir_historico_prioritario']);
        });
        Schema::table('entregas_documento', function (Blueprint $table) {
            $table->dropColumn('aprovado');
        });
    }
};
