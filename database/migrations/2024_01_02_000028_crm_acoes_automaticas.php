<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('acoes_automaticas_crm')) {
            Schema::create('acoes_automaticas_crm', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->string('gatilho'); // novo_interessado | nova_oportunidade | mudanca_etapa | oportunidade_ganha | oportunidade_perdida
                $table->string('acao'); // criar_atividade | enviar_email | notificar_consultor | mover_etapa
                $table->text('detalhes')->nullable();
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('acoes_automaticas_crm');
    }
};
