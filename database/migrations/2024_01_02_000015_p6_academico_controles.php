<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Controle de Horas Complementares (239)
        if (!Schema::hasTable('horas_complementares')) {
            Schema::create('horas_complementares', function (Blueprint $table) {
                $table->id();
                $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
                $table->string('tipo'); // Complementar, Estágio, Extensão
                $table->decimal('quantidade', 8, 2)->default(0);
                $table->string('situacao')->default('Parcial'); // Parcial, Aprovado
                $table->text('descricao')->nullable();
                $table->timestamps();
            });
        }

        // Controle de Prática Supervisionada (90)
        if (!Schema::hasTable('praticas_supervisionadas')) {
            Schema::create('praticas_supervisionadas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
                $table->foreignId('disciplina_id')->constrained('disciplinas')->cascadeOnDelete();
                $table->decimal('quantidade', 8, 2)->default(0);
                $table->string('situacao')->default('Parcial'); // Parcial, Aprovado
                $table->timestamps();
            });
        }

        // Liberar Lançamento de Frequência (262)
        if (!Schema::hasTable('liberacoes_frequencia')) {
            Schema::create('liberacoes_frequencia', function (Blueprint $table) {
                $table->id();
                $table->foreignId('turma_montada_id')->constrained('turmas_montadas')->cascadeOnDelete();
                $table->foreignId('profissional_id')->nullable()->constrained('profissionais')->nullOnDelete();
                $table->date('data_inicio');
                $table->date('data_fim');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('liberacoes_frequencia');
        Schema::dropIfExists('praticas_supervisionadas');
        Schema::dropIfExists('horas_complementares');
    }
};
