<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movimentacoes_estoque', function (Blueprint $table) {
            if (!Schema::hasColumn('movimentacoes_estoque', 'data_movimentacao')) {
                $table->date('data_movimentacao')->nullable()->after('tipo');
            }
            if (!Schema::hasColumn('movimentacoes_estoque', 'deposito_origem_id')) {
                $table->foreignId('deposito_origem_id')->nullable()->after('deposito_id')->constrained('depositos')->nullOnDelete();
            }
            if (!Schema::hasColumn('movimentacoes_estoque', 'deposito_destino_id')) {
                $table->foreignId('deposito_destino_id')->nullable()->after('deposito_origem_id')->constrained('depositos')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('movimentacoes_estoque', function (Blueprint $table) {
            foreach (['deposito_origem_id', 'deposito_destino_id'] as $fk) {
                if (Schema::hasColumn('movimentacoes_estoque', $fk)) {
                    $table->dropConstrainedForeignKey($fk);
                }
            }
            if (Schema::hasColumn('movimentacoes_estoque', 'data_movimentacao')) {
                $table->dropColumn('data_movimentacao');
            }
        });
    }
};
