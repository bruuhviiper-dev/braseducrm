<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('turmas_montadas', function (Blueprint $table) {
            if (!Schema::hasColumn('turmas_montadas', 'sigla')) {
                $table->string('sigla')->nullable()->after('periodo_letivo_id');
            }
            if (!Schema::hasColumn('turmas_montadas', 'ativo')) {
                $table->boolean('ativo')->default(true)->after('situacao');
            }
        });
    }

    public function down(): void
    {
        Schema::table('turmas_montadas', function (Blueprint $table) {
            foreach (['ativo', 'sigla'] as $col) {
                if (Schema::hasColumn('turmas_montadas', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
