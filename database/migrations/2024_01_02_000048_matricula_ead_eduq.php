<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matriculas_ead', function (Blueprint $table) {
            if (!Schema::hasColumn('matriculas_ead', 'operador_id')) {
                $table->foreignId('operador_id')->nullable()->after('aluno_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('matriculas_ead', 'matricular_por_agrupador')) {
                $table->boolean('matricular_por_agrupador')->default(false)->after('permitir_inadimplente');
            }
            if (!Schema::hasColumn('matriculas_ead', 'nao_enviar_email')) {
                $table->boolean('nao_enviar_email')->default(false)->after('matricular_por_agrupador');
            }
            if (!Schema::hasColumn('matriculas_ead', 'apresentar_nao_confirmado')) {
                $table->boolean('apresentar_nao_confirmado')->default(false)->after('nao_enviar_email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('matriculas_ead', function (Blueprint $table) {
            if (Schema::hasColumn('matriculas_ead', 'operador_id')) {
                $table->dropConstrainedForeignKey('operador_id');
            }
            foreach (['apresentar_nao_confirmado', 'nao_enviar_email', 'matricular_por_agrupador'] as $col) {
                if (Schema::hasColumn('matriculas_ead', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
