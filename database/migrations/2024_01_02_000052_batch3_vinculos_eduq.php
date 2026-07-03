<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matrizes_curriculares', function (Blueprint $t) {
            foreach (['codigo_emec', 'ato_autorizacao', 'ato_reconhecimento', 'ato_renovacao'] as $c) {
                if (!Schema::hasColumn('matrizes_curriculares', $c)) $t->string($c)->nullable();
            }
        });
        Schema::table('turmas', function (Blueprint $t) {
            if (!Schema::hasColumn('turmas', 'comissionavel')) $t->boolean('comissionavel')->default(false);
            if (!Schema::hasColumn('turmas', 'cor')) $t->string('cor', 20)->nullable();
            if (!Schema::hasColumn('turmas', 'modelo_documento_id')) $t->foreignId('modelo_documento_id')->nullable()->constrained('modelos_documento')->nullOnDelete();
            if (!Schema::hasColumn('turmas', 'conta_id')) $t->foreignId('conta_id')->nullable()->constrained('contas_bancarias')->nullOnDelete();
            if (!Schema::hasColumn('turmas', 'cidade_aulas')) $t->string('cidade_aulas')->nullable();
            if (!Schema::hasColumn('turmas', 'tipo_turma')) $t->string('tipo_turma')->nullable();
            if (!Schema::hasColumn('turmas', 'descricao_horario')) $t->text('descricao_horario')->nullable();
            if (!Schema::hasColumn('turmas', 'nao_enviar_contrato')) $t->boolean('nao_enviar_contrato')->default(false);
        });
        Schema::table('interessados', function (Blueprint $t) {
            if (!Schema::hasColumn('interessados', 'codigo_pais')) $t->string('codigo_pais', 10)->nullable()->default('+55');
        });
        Schema::table('atendimentos', function (Blueprint $t) {
            if (!Schema::hasColumn('atendimentos', 'responsavel_id')) $t->foreignId('responsavel_id')->nullable()->constrained('users')->nullOnDelete();
            if (!Schema::hasColumn('atendimentos', 'canal')) $t->string('canal')->nullable();
            if (!Schema::hasColumn('atendimentos', 'portal_aluno')) $t->boolean('portal_aluno')->default(false);
            if (!Schema::hasColumn('atendimentos', 'precisa_retorno')) $t->boolean('precisa_retorno')->default(false);
            if (!Schema::hasColumn('atendimentos', 'departamentos_responsavel')) $t->boolean('departamentos_responsavel')->default(false);
        });
    }

    public function down(): void
    {
        // colunas aditivas; sem rollback destrutivo
    }
};
