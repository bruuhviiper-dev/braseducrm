<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cursos_ead', function (Blueprint $table) {
            if (!Schema::hasColumn('cursos_ead', 'tutor_id')) {
                $table->foreignId('tutor_id')->nullable()->after('ativo')->constrained('profissionais')->nullOnDelete();
            }
            if (!Schema::hasColumn('cursos_ead', 'agrupador_curso_id')) {
                $table->foreignId('agrupador_curso_id')->nullable()->after('tutor_id')->constrained('agrupadores_curso')->nullOnDelete();
            }
            if (!Schema::hasColumn('cursos_ead', 'sub_agrupador_curso_id')) {
                $table->foreignId('sub_agrupador_curso_id')->nullable()->after('agrupador_curso_id')->constrained('sub_agrupadores_curso')->nullOnDelete();
            }
        });

        if (!Schema::hasTable('modulos_ead')) {
            Schema::create('modulos_ead', function (Blueprint $table) {
                $table->id();
                $table->foreignId('curso_ead_id')->constrained('cursos_ead')->cascadeOnDelete();
                $table->string('nome');
                $table->integer('ordem')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('aulas_ead')) {
            Schema::create('aulas_ead', function (Blueprint $table) {
                $table->id();
                $table->foreignId('modulo_ead_id')->constrained('modulos_ead')->cascadeOnDelete();
                $table->string('titulo');
                $table->string('tipo')->default('video'); // video | texto | questionario
                $table->foreignId('video_ead_id')->nullable()->constrained('videos_ead')->nullOnDelete();
                $table->text('conteudo')->nullable();
                $table->integer('ordem')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('curso_ead_tag')) {
            Schema::create('curso_ead_tag', function (Blueprint $table) {
                $table->id();
                $table->foreignId('curso_ead_id')->constrained('cursos_ead')->cascadeOnDelete();
                $table->foreignId('tag_curso_ead_id')->constrained('tags_curso_ead')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('curso_ead_tag');
        Schema::dropIfExists('aulas_ead');
        Schema::dropIfExists('modulos_ead');
        Schema::table('cursos_ead', function (Blueprint $table) {
            foreach (['sub_agrupador_curso_id', 'agrupador_curso_id', 'tutor_id'] as $col) {
                if (Schema::hasColumn('cursos_ead', $col)) {
                    $table->dropConstrainedForeignKey($col);
                }
            }
        });
    }
};
