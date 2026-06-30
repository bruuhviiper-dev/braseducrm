<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tags_curso_ead')) {
            Schema::create('tags_curso_ead', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }

        // Campos extras na matrícula EAD (flags do EDUQ)
        Schema::table('matriculas_ead', function (Blueprint $table) {
            if (!Schema::hasColumn('matriculas_ead', 'ativo')) {
                $table->boolean('ativo')->default(true)->after('situacao');
            }
            if (!Schema::hasColumn('matriculas_ead', 'permitir_inadimplente')) {
                $table->boolean('permitir_inadimplente')->default(false)->after('ativo');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tags_curso_ead');
        Schema::table('matriculas_ead', function (Blueprint $table) {
            foreach (['ativo', 'permitir_inadimplente'] as $c) {
                if (Schema::hasColumn('matriculas_ead', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};
