<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disciplinas', function (Blueprint $table) {
            if (!Schema::hasColumn('disciplinas', 'estrutura_plano_ensino_id')) {
                $table->unsignedBigInteger('estrutura_plano_ensino_id')->nullable()->after('ementa');
            }
        });
    }

    public function down(): void
    {
        Schema::table('disciplinas', function (Blueprint $table) {
            if (Schema::hasColumn('disciplinas', 'estrutura_plano_ensino_id')) {
                $table->dropColumn('estrutura_plano_ensino_id');
            }
        });
    }
};
