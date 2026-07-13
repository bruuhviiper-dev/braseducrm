<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Layout de emissão compartilhável entre operadores (EDUQ: "Layout pode ser usado por outros operadores?"). */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('emissao_layouts', 'compartilhado')) {
            Schema::table('emissao_layouts', function (Blueprint $table) {
                $table->boolean('compartilhado')->default(false);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('emissao_layouts', 'compartilhado')) {
            Schema::table('emissao_layouts', function (Blueprint $table) {
                $table->dropColumn('compartilhado');
            });
        }
    }
};
