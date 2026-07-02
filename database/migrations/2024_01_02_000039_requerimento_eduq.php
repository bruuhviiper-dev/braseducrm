<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requerimentos', function (Blueprint $table) {
            if (!Schema::hasColumn('requerimentos', 'vinculo_tipo')) {
                $table->string('vinculo_tipo')->default('matricula')->after('aluno_id'); // pessoa|matricula|matricula_ead
            }
            if (!Schema::hasColumn('requerimentos', 'pessoa_id')) {
                $table->foreignId('pessoa_id')->nullable()->after('vinculo_tipo')->constrained('pessoas')->nullOnDelete();
            }
            if (!Schema::hasColumn('requerimentos', 'matricula_ead_id')) {
                $table->foreignId('matricula_ead_id')->nullable()->after('matricula_id')->constrained('matriculas_ead')->nullOnDelete();
            }
            if (!Schema::hasColumn('requerimentos', 'anotacoes')) {
                $table->text('anotacoes')->nullable()->after('observacoes');
            }
        });

        // aluno_id passa a ser opcional (requerimento pode ser vinculado só a uma Pessoa)
        try {
            Schema::table('requerimentos', function (Blueprint $table) {
                $table->foreignId('aluno_id')->nullable()->change();
            });
        } catch (\Throwable $e) {
            // se o driver não suportar change(), seguimos — o controller sempre deriva aluno_id quando possível
        }
    }

    public function down(): void
    {
        Schema::table('requerimentos', function (Blueprint $table) {
            foreach (['pessoa_id', 'matricula_ead_id'] as $fk) {
                if (Schema::hasColumn('requerimentos', $fk)) {
                    $table->dropConstrainedForeignKey($fk);
                }
            }
            foreach (['anotacoes', 'vinculo_tipo'] as $col) {
                if (Schema::hasColumn('requerimentos', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
