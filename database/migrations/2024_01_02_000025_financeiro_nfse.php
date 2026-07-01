<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('configuracoes_nfse')) {
            Schema::create('configuracoes_nfse', function (Blueprint $table) {
                $table->id();
                $table->string('ambiente')->default('homologacao'); // homologacao | producao
                $table->string('regime_tributario')->nullable(); // simples_nacional | lucro_presumido | lucro_real
                $table->string('inscricao_municipal')->nullable();
                $table->string('serie_rps')->nullable();
                $table->integer('numero_rps_atual')->default(1);
                $table->string('codigo_servico')->nullable();
                $table->decimal('aliquota_iss', 5, 2)->default(0);
                $table->boolean('iss_retido')->default(false);
                $table->text('discriminacao_padrao')->nullable();
                $table->boolean('ativo')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracoes_nfse');
    }
};
