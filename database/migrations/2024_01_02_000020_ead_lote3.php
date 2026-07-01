<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sub_agrupadores_curso')) {
            Schema::create('sub_agrupadores_curso', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->foreignId('agrupador_curso_id')->nullable()->constrained('agrupadores_curso')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('foruns_ead')) {
            Schema::create('foruns_ead', function (Blueprint $table) {
                $table->id();
                $table->string('titulo');
                $table->foreignId('curso_ead_id')->nullable()->constrained('cursos_ead')->nullOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('forum_mensagens')) {
            Schema::create('forum_mensagens', function (Blueprint $table) {
                $table->id();
                $table->foreignId('forum_ead_id')->constrained('foruns_ead')->cascadeOnDelete();
                $table->foreignId('pessoa_id')->nullable()->constrained('pessoas')->nullOnDelete();
                $table->text('mensagem');
                $table->boolean('do_tutor')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('geradores_avaliacao')) {
            Schema::create('geradores_avaliacao', function (Blueprint $table) {
                $table->id();
                $table->string('descricao');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('gerador_avaliacao_parametros')) {
            Schema::create('gerador_avaliacao_parametros', function (Blueprint $table) {
                $table->id();
                $table->foreignId('gerador_avaliacao_id')->constrained('geradores_avaliacao')->cascadeOnDelete();
                $table->foreignId('tag_questao_id')->nullable()->constrained('tags_questao')->nullOnDelete();
                $table->integer('quantidade')->default(1);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('gerador_avaliacao_parametros');
        Schema::dropIfExists('geradores_avaliacao');
        Schema::dropIfExists('forum_mensagens');
        Schema::dropIfExists('foruns_ead');
        Schema::dropIfExists('sub_agrupadores_curso');
    }
};
