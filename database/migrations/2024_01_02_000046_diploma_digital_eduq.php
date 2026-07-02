<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('diplomas_digitais', function (Blueprint $table) {
            if (!Schema::hasColumn('diplomas_digitais', 'matricula_id')) {
                $table->foreignId('matricula_id')->nullable()->after('aluno_id')->constrained('matriculas')->nullOnDelete();
            }
            if (!Schema::hasColumn('diplomas_digitais', 'data_solicitacao')) {
                $table->date('data_solicitacao')->nullable()->after('situacao');
            }
            if (!Schema::hasColumn('diplomas_digitais', 'data_registro')) {
                $table->date('data_registro')->nullable()->after('data_solicitacao');
            }
        });
    }

    public function down(): void
    {
        Schema::table('diplomas_digitais', function (Blueprint $table) {
            if (Schema::hasColumn('diplomas_digitais', 'matricula_id')) {
                $table->dropConstrainedForeignKey('matricula_id');
            }
            foreach (['data_registro', 'data_solicitacao'] as $col) {
                if (Schema::hasColumn('diplomas_digitais', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
