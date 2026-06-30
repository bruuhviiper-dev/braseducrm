<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('videos_ead')) {
            Schema::create('videos_ead', function (Blueprint $table) {
                $table->id();
                $table->string('titulo');
                $table->text('descricao')->nullable();
                $table->string('arquivo')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('questoes_avulsas')) {
            Schema::create('questoes_avulsas', function (Blueprint $table) {
                $table->id();
                $table->boolean('ativo')->default(true);
                $table->string('titulo')->nullable();
                $table->text('enunciado');
                $table->string('tipo'); // multipla_escolha, verdadeiro_falso, dissertativa
                $table->decimal('peso', 8, 2)->nullable();
                $table->foreignId('tag_questao_id')->nullable()->constrained('tags_questao')->nullOnDelete();
                $table->text('explicacao')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('alternativas_questao')) {
            Schema::create('alternativas_questao', function (Blueprint $table) {
                $table->id();
                $table->foreignId('questao_avulsa_id')->constrained('questoes_avulsas')->cascadeOnDelete();
                $table->text('texto');
                $table->boolean('correta')->default(false);
                $table->integer('ordem')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('alternativas_questao');
        Schema::dropIfExists('questoes_avulsas');
        Schema::dropIfExists('videos_ead');
    }
};
