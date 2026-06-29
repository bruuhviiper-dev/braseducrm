<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cadastro de Escola (8): campos que faltavam pra igualar o EDUQ
        Schema::table('escolas', function (Blueprint $table) {
            if (!Schema::hasColumn('escolas', 'telefone')) {
                $table->string('telefone', 20)->nullable()->after('nome');
            }
            if (!Schema::hasColumn('escolas', 'tipo_escola')) {
                // Privada, Pública Estadual, Pública Municipal, Pública Federal, Conveniada
                $table->string('tipo_escola', 30)->nullable()->after('uf');
            }
        });

        // Cadastro de Grade de Horário (36): flag ativo + horários de aula (1:N)
        Schema::table('grades_horario', function (Blueprint $table) {
            if (!Schema::hasColumn('grades_horario', 'ativo')) {
                $table->boolean('ativo')->default(true)->after('turno_id');
            }
        });

        if (!Schema::hasTable('grade_horario_aulas')) {
            Schema::create('grade_horario_aulas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('grade_horario_id')->constrained('grades_horario')->cascadeOnDelete();
                $table->time('hora_inicio');
                $table->time('hora_fim');
                // Hora-aula: campo de definição livre (prioridade sobre o intervalo no cálculo - EDUQ tela 302)
                $table->string('hora_aula', 8)->nullable();
                $table->integer('ordem')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_horario_aulas');

        Schema::table('grades_horario', function (Blueprint $table) {
            if (Schema::hasColumn('grades_horario', 'ativo')) {
                $table->dropColumn('ativo');
            }
        });

        Schema::table('escolas', function (Blueprint $table) {
            foreach (['telefone', 'tipo_escola'] as $col) {
                if (Schema::hasColumn('escolas', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
