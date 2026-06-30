<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Manutenção de Exame de Nível (183)
        if (!Schema::hasTable('exames_nivel')) {
            Schema::create('exames_nivel', function (Blueprint $table) {
                $table->id();
                $table->foreignId('aluno_id')->constrained('alunos')->cascadeOnDelete();
                $table->foreignId('disciplina_id')->constrained('disciplinas')->cascadeOnDelete();
                $table->decimal('nota', 5, 2)->nullable();
                $table->string('situacao')->default('Pendente'); // Aprovado, Reprovado, Pendente
                $table->date('data_exame')->nullable();
                $table->timestamps();
            });
        }

        // Controle de Rematrículas (279)
        if (!Schema::hasTable('rematriculas')) {
            Schema::create('rematriculas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
                $table->foreignId('futura_turma_id')->nullable()->constrained('turmas')->nullOnDelete();
                $table->date('data_abertura')->nullable();
                // Pendente de assinatura de contrato, Confirmada, Cancelada
                $table->string('situacao')->default('Pendente de assinatura de contrato');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('rematriculas');
        Schema::dropIfExists('exames_nivel');
    }
};
