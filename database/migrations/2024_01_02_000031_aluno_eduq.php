<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            if (!Schema::hasColumn('alunos', 'titularidade_id')) {
                $table->foreignId('titularidade_id')->nullable()->after('forma_ingresso_id')->constrained('titularidades')->nullOnDelete();
            }
            if (!Schema::hasColumn('alunos', 'informacoes_adicionais')) {
                $table->text('informacoes_adicionais')->nullable()->after('data_ingresso');
            }
            if (!Schema::hasColumn('alunos', 'tipo_sanguineo')) {
                $table->string('tipo_sanguineo')->nullable()->after('informacoes_adicionais');
            }
            if (!Schema::hasColumn('alunos', 'alergia_id')) {
                $table->foreignId('alergia_id')->nullable()->after('tipo_sanguineo')->constrained('alergias')->nullOnDelete();
            }
            if (!Schema::hasColumn('alunos', 'necessidade_especial_id')) {
                $table->foreignId('necessidade_especial_id')->nullable()->after('alergia_id')->constrained('necessidades_especiais')->nullOnDelete();
            }
            if (!Schema::hasColumn('alunos', 'observacoes_saude')) {
                $table->text('observacoes_saude')->nullable()->after('necessidade_especial_id');
            }
        });

        if (!Schema::hasTable('responsaveis_aluno')) {
            Schema::create('responsaveis_aluno', function (Blueprint $table) {
                $table->id();
                $table->foreignId('aluno_id')->constrained('alunos')->cascadeOnDelete();
                $table->string('nome');
                $table->string('parentesco')->nullable(); // pai, mãe, responsável...
                $table->string('cpf')->nullable();
                $table->string('telefone')->nullable();
                $table->string('email')->nullable();
                $table->boolean('principal')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('formacoes_aluno')) {
            Schema::create('formacoes_aluno', function (Blueprint $table) {
                $table->id();
                $table->foreignId('aluno_id')->constrained('alunos')->cascadeOnDelete();
                $table->string('nivel')->nullable(); // fundamental, médio, graduação...
                $table->string('instituicao')->nullable();
                $table->string('curso')->nullable();
                $table->integer('ano_conclusao')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('formacoes_aluno');
        Schema::dropIfExists('responsaveis_aluno');
        Schema::table('alunos', function (Blueprint $table) {
            foreach (['necessidade_especial_id', 'alergia_id', 'titularidade_id'] as $fk) {
                if (Schema::hasColumn('alunos', $fk)) {
                    $table->dropConstrainedForeignKey($fk);
                }
            }
            foreach (['observacoes_saude', 'tipo_sanguineo', 'informacoes_adicionais'] as $col) {
                if (Schema::hasColumn('alunos', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
