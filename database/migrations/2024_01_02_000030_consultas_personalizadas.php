<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('consultas_personalizadas')) {
            Schema::create('consultas_personalizadas', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->string('entidade');
                $table->json('campos')->nullable();
                $table->string('filtro_campo')->nullable();
                $table->string('filtro_operador')->nullable();
                $table->string('filtro_valor')->nullable();
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('consultas_personalizadas');
    }
};
