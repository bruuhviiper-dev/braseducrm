<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classificacoes_ged', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->foreignId('pai_id')->nullable()->constrained('classificacoes_ged')->nullOnDelete();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('documentos_ged', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classificacao_ged_id')->nullable()->constrained('classificacoes_ged')->nullOnDelete();
            $table->string('titulo');
            $table->string('arquivo')->nullable();
            $table->string('tipo_arquivo', 50)->nullable();
            $table->text('observacoes')->nullable();
            $table->foreignId('enviado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('atos_regulatorios', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['credenciamento', 'recredenciamento', 'autorizacao', 'reconhecimento', 'renovacao', 'outro'])->default('autorizacao');
            $table->string('numero')->nullable();
            $table->foreignId('curso_id')->nullable()->constrained('cursos')->nullOnDelete();
            $table->date('data_publicacao')->nullable();
            $table->date('validade')->nullable();
            $table->string('orgao')->nullable();
            $table->text('observacoes')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('diplomas_digitais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->cascadeOnDelete();
            $table->foreignId('curso_id')->nullable()->constrained('cursos')->nullOnDelete();
            $table->string('numero_registro')->nullable();
            $table->enum('situacao', ['pendente', 'emitido', 'assinado', 'registrado'])->default('pendente');
            $table->date('data_emissao')->nullable();
            $table->date('data_colacao')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diplomas_digitais');
        Schema::dropIfExists('atos_regulatorios');
        Schema::dropIfExists('documentos_ged');
        Schema::dropIfExists('classificacoes_ged');
    }
};
