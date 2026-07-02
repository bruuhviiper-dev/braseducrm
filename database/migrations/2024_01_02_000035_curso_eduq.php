<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            if (!Schema::hasColumn('cursos', 'modelo_documento_id')) {
                $table->foreignId('modelo_documento_id')->nullable()->after('instituicao_ensino_id')->constrained('modelos_documento')->nullOnDelete();
            }
            if (!Schema::hasColumn('cursos', 'bloquear_menores')) {
                $table->boolean('bloquear_menores')->default(false)->after('duracao_meses');
            }
            if (!Schema::hasColumn('cursos', 'nao_gerar_nf')) {
                $table->boolean('nao_gerar_nf')->default(false)->after('bloquear_menores');
            }
            if (!Schema::hasColumn('cursos', 'valor_comissao')) {
                $table->decimal('valor_comissao', 10, 2)->nullable()->after('nao_gerar_nf');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            if (Schema::hasColumn('cursos', 'modelo_documento_id')) {
                $table->dropConstrainedForeignKey('modelo_documento_id');
            }
            foreach (['valor_comissao', 'nao_gerar_nf', 'bloquear_menores'] as $col) {
                if (Schema::hasColumn('cursos', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
