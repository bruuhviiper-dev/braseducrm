<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('turmas', function (Blueprint $t) {
            $t->foreignId('turno_id')->nullable()->change();
            $t->foreignId('instituicao_ensino_id')->nullable()->change();
            $t->string('codigo', 50)->nullable()->change();
        });
    }

    public function down(): void
    {
    }
};
