<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respostas_questionario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionario_id')->constrained('questionarios')->cascadeOnDelete();
            $table->string('respondente_nome')->nullable();
            $table->string('respondente_email')->nullable();
            $table->timestamps();
        });

        Schema::create('respostas_questao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resposta_questionario_id')->constrained('respostas_questionario')->cascadeOnDelete();
            $table->foreignId('questao_id')->constrained('questoes')->cascadeOnDelete();
            $table->decimal('valor', 5, 2)->nullable();
            $table->text('texto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respostas_questao');
        Schema::dropIfExists('respostas_questionario');
    }
};
