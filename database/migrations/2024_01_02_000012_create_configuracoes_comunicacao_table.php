<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracoes_comunicacao', function (Blueprint $table) {
            $table->id();
            $table->string('remetente_nome')->nullable();
            $table->string('remetente_email')->nullable();
            $table->string('canal_padrao', 20)->default('email'); // email | sms | whatsapp
            $table->text('assinatura')->nullable();
            $table->boolean('enviar_aviso_vencimento')->default(false);
            $table->integer('dias_aviso_vencimento')->default(3);
            $table->boolean('enviar_aviso_cobranca')->default(false);
            $table->json('configuracoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracoes_comunicacao');
    }
};
