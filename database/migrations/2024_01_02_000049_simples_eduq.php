<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salas', function (Blueprint $table) {
            if (!Schema::hasColumn('salas', 'sigla')) {
                $table->string('sigla')->nullable()->after('nome');
            }
        });
        Schema::table('periodos_letivos', function (Blueprint $table) {
            if (!Schema::hasColumn('periodos_letivos', 'descricao_historico')) {
                $table->string('descricao_historico')->nullable()->after('nome');
            }
        });
    }

    public function down(): void
    {
        Schema::table('salas', function (Blueprint $table) {
            if (Schema::hasColumn('salas', 'sigla')) $table->dropColumn('sigla');
        });
        Schema::table('periodos_letivos', function (Blueprint $table) {
            if (Schema::hasColumn('periodos_letivos', 'descricao_historico')) $table->dropColumn('descricao_historico');
        });
    }
};
