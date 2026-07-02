<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planos_ensino', function (Blueprint $table) {
            if (!Schema::hasColumn('planos_ensino', 'ocultar_portal')) {
                $table->boolean('ocultar_portal')->default(false)->after('estrutura_plano_id');
            }
            if (!Schema::hasColumn('planos_ensino', 'anexo_path')) {
                $table->string('anexo_path')->nullable()->after('ocultar_portal');
            }
        });
    }

    public function down(): void
    {
        Schema::table('planos_ensino', function (Blueprint $table) {
            if (Schema::hasColumn('planos_ensino', 'ocultar_portal')) {
                $table->dropColumn('ocultar_portal');
            }
        });
    }
};
