<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('numeros_whatsapp')) {
            Schema::create('numeros_whatsapp', function (Blueprint $table) {
                $table->id();
                $table->string('numero');
                $table->string('descricao')->nullable();
                $table->boolean('principal')->default(false);
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('notificacoes_aluno')) {
            Schema::create('notificacoes_aluno', function (Blueprint $table) {
                $table->id();
                $table->foreignId('aluno_id')->nullable()->constrained('alunos')->cascadeOnDelete();
                $table->string('titulo');
                $table->text('mensagem')->nullable();
                $table->string('tipo')->default('info'); // info | aviso | sucesso | urgente
                $table->boolean('para_todos')->default(false);
                $table->boolean('lida')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notificacoes_aluno');
        Schema::dropIfExists('numeros_whatsapp');
    }
};
