<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tabelas_avaliacao', function (Blueprint $t) {
            if (!Schema::hasColumn('tabelas_avaliacao', 'formula')) $t->string('formula', 250)->nullable();
            if (!Schema::hasColumn('tabelas_avaliacao', 'visibilidade_operador')) $t->boolean('visibilidade_operador')->default(false);
            if (!Schema::hasColumn('tabelas_avaliacao', 'operador_id')) $t->foreignId('operador_id')->nullable()->constrained('users')->nullOnDelete();
        });
        Schema::table('profissionais', function (Blueprint $t) {
            if (!Schema::hasColumn('profissionais', 'data_admissao')) $t->date('data_admissao')->nullable();
            if (!Schema::hasColumn('profissionais', 'data_demissao')) $t->date('data_demissao')->nullable();
            if (!Schema::hasColumn('profissionais', 'cargo')) $t->string('cargo')->nullable();
            if (!Schema::hasColumn('profissionais', 'informacoes_adicionais')) $t->text('informacoes_adicionais')->nullable();
            if (!Schema::hasColumn('profissionais', 'informacoes_curriculares')) $t->text('informacoes_curriculares')->nullable();
            if (!Schema::hasColumn('profissionais', 'assinatura_path')) $t->string('assinatura_path')->nullable();
        });
        Schema::table('plano_contas', function (Blueprint $t) {
            if (!Schema::hasColumn('plano_contas', 'mascara_filhos')) $t->string('mascara_filhos')->nullable();
            if (!Schema::hasColumn('plano_contas', 'tesouraria')) $t->boolean('tesouraria')->default(false);
            if (!Schema::hasColumn('plano_contas', 'identificador_integracao')) $t->string('identificador_integracao')->nullable();
        });
        Schema::table('users', function (Blueprint $t) {
            if (!Schema::hasColumn('users', 'is_admin')) $t->boolean('is_admin')->default(false);
            if (!Schema::hasColumn('users', 'exigir_troca_senha')) $t->boolean('exigir_troca_senha')->default(false);
            if (!Schema::hasColumn('users', 'profissional_id')) $t->foreignId('profissional_id')->nullable()->constrained('profissionais')->nullOnDelete();
        });
        Schema::table('documentos', function (Blueprint $t) {
            if (!Schema::hasColumn('documentos', 'sigla')) $t->string('sigla', 20)->nullable();
            if (!Schema::hasColumn('documentos', 'tipo_ged')) $t->string('tipo_ged')->nullable();
            if (!Schema::hasColumn('documentos', 'idade_minima')) $t->unsignedInteger('idade_minima')->nullable();
            if (!Schema::hasColumn('documentos', 'visibilidade_matriz')) $t->boolean('visibilidade_matriz')->default(false);
            if (!Schema::hasColumn('documentos', 'obrigatorio_generos')) $t->boolean('obrigatorio_generos')->default(true);
            if (!Schema::hasColumn('documentos', 'grau')) $t->string('grau')->nullable();
            if (!Schema::hasColumn('documentos', 'forma_ingresso_id')) $t->foreignId('forma_ingresso_id')->nullable()->constrained('formas_ingresso')->nullOnDelete();
        });
    }

    public function down(): void
    {
        // simples: colunas ficam (SQLite drops são custosos); noop seguro
    }
};
