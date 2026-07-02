<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movimentacoes_exemplar', function (Blueprint $table) {
            if (!Schema::hasColumn('movimentacoes_exemplar', 'operador_id')) {
                $table->foreignId('operador_id')->nullable()->after('pessoa_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('movimentacoes_exemplar', 'renovacoes')) {
                $table->integer('renovacoes')->default(0)->after('multa');
            }
        });
    }

    public function down(): void
    {
        Schema::table('movimentacoes_exemplar', function (Blueprint $table) {
            if (Schema::hasColumn('movimentacoes_exemplar', 'operador_id')) {
                $table->dropConstrainedForeignKey('operador_id');
            }
            if (Schema::hasColumn('movimentacoes_exemplar', 'renovacoes')) {
                $table->dropColumn('renovacoes');
            }
        });
    }
};
