<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('turmas', function (Blueprint $table) {
            if (!Schema::hasColumn('turmas', 'finalizada')) {
                $table->boolean('finalizada')->default(false)->after('situacao');
            }
        });
    }

    public function down(): void
    {
        Schema::table('turmas', function (Blueprint $table) {
            if (Schema::hasColumn('turmas', 'finalizada')) {
                $table->dropColumn('finalizada');
            }
        });
    }
};
