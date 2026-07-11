<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Campos que faltavam p/ igualar os cadastros do módulo Acadêmico ao EDUQ ao vivo. */
return new class extends Migration
{
    public function up(): void
    {
        // 169 Tag de Matrícula: toggle "Exige checklist?"
        if (!Schema::hasColumn('tags_matricula', 'exige_checklist')) {
            Schema::table('tags_matricula', function (Blueprint $table) {
                $table->boolean('exige_checklist')->default(false);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tags_matricula', 'exige_checklist')) {
            Schema::table('tags_matricula', function (Blueprint $table) {
                $table->dropColumn('exige_checklist');
            });
        }
    }
};
