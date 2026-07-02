<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cursos_ead', function (Blueprint $table) {
            if (!Schema::hasColumn('cursos_ead', 'titulo_portal')) {
                $table->string('titulo_portal')->nullable()->after('nome');
            }
            if (!Schema::hasColumn('cursos_ead', 'turma_montada_id')) {
                $table->foreignId('turma_montada_id')->nullable()->after('sub_agrupador_curso_id')->constrained('turmas_montadas')->nullOnDelete();
            }
            if (!Schema::hasColumn('cursos_ead', 'disciplina_id')) {
                $table->foreignId('disciplina_id')->nullable()->after('turma_montada_id')->constrained('disciplinas')->nullOnDelete();
            }
            if (!Schema::hasColumn('cursos_ead', 'modelo_documento_id')) {
                $table->unsignedBigInteger('modelo_documento_id')->nullable()->after('disciplina_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cursos_ead', function (Blueprint $table) {
            foreach (['turma_montada_id', 'disciplina_id'] as $fk) {
                if (Schema::hasColumn('cursos_ead', $fk)) {
                    $table->dropConstrainedForeignKey($fk);
                }
            }
            foreach (['modelo_documento_id', 'titulo_portal'] as $col) {
                if (Schema::hasColumn('cursos_ead', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
