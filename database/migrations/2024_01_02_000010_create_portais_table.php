<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracoes_portal', function (Blueprint $table) {
            $table->id();
            $table->string('nome_portal')->default('Portal do Aluno');
            $table->string('cor_primaria', 7)->default('#3B82F6');
            $table->text('mensagem_boas_vindas')->nullable();
            $table->boolean('exibe_financeiro')->default(true);
            $table->boolean('exibe_boletim')->default(true);
            $table->boolean('exibe_documentos')->default(true);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('pastas_portal', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->integer('ordem')->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('publicacoes_portal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasta_portal_id')->nullable()->constrained('pastas_portal')->nullOnDelete();
            $table->string('titulo');
            $table->longText('conteudo')->nullable();
            $table->string('arquivo')->nullable();
            $table->date('publicado_em')->nullable();
            $table->boolean('ativo')->default(true);
            $table->foreignId('publicado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publicacoes_portal');
        Schema::dropIfExists('pastas_portal');
        Schema::dropIfExists('configuracoes_portal');
    }
};
