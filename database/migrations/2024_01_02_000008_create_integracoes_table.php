<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('integracoes', function (Blueprint $table) {
            $table->id();
            $table->string('chave')->unique(); // rd_station, gateway_cartao, sms, whatsapp, nfe, boleto
            $table->string('nome');
            $table->boolean('ativo')->default(false);
            $table->json('credenciais')->nullable();
            $table->timestamp('ultima_sincronizacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('integracoes');
    }
};
