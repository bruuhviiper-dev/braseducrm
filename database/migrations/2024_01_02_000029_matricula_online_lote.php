<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tags_matricula_online')) {
            Schema::create('tags_matricula_online', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->string('cor')->nullable();
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('cupons_personalizados')) {
            Schema::create('cupons_personalizados', function (Blueprint $table) {
                $table->id();
                $table->string('codigo')->unique();
                $table->string('beneficiario')->nullable();
                $table->string('tipo_desconto')->default('percentual'); // percentual | valor
                $table->decimal('valor_desconto', 15, 2)->default(0);
                $table->date('validade')->nullable();
                $table->boolean('usado')->default(false);
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cupons_personalizados');
        Schema::dropIfExists('tags_matricula_online');
    }
};
