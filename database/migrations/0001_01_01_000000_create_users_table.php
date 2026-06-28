<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departamentos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('grupo_operadores', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('funcoes', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo')->unique();
            $table->string('nome');
            $table->string('modulo');
            $table->string('icone')->nullable();
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('grupo_permissoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_operador_id')->constrained('grupo_operadores')->cascadeOnDelete();
            $table->foreignId('funcao_id')->constrained('funcoes')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('login')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->foreignId('grupo_operador_id')->nullable()->constrained('grupo_operadores')->nullOnDelete();
            $table->foreignId('departamento_id')->nullable()->constrained('departamentos')->nullOnDelete();
            $table->boolean('ativo')->default(true);
            $table->timestamp('ultimo_acesso')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('user_permissoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('funcao_id')->constrained('funcoes')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('favoritos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('funcao_id')->constrained('funcoes')->cascadeOnDelete();
            $table->integer('ordem')->default(0);
            $table->timestamps();
        });

        Schema::create('acessos_recentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('funcao_id')->constrained('funcoes')->cascadeOnDelete();
            $table->timestamp('acessado_em');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acessos_recentes');
        Schema::dropIfExists('favoritos');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('user_permissoes');
        Schema::dropIfExists('users');
        Schema::dropIfExists('grupo_permissoes');
        Schema::dropIfExists('funcoes');
        Schema::dropIfExists('grupo_operadores');
        Schema::dropIfExists('departamentos');
    }
};
