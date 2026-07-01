<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cheques')) {
            Schema::create('cheques', function (Blueprint $table) {
                $table->id();
                $table->string('tipo')->default('recebido'); // recebido | emitido
                $table->string('numero');
                $table->foreignId('banco_id')->nullable()->constrained('bancos')->nullOnDelete();
                $table->string('agencia')->nullable();
                $table->string('conta')->nullable();
                $table->string('emitente')->nullable();
                $table->decimal('valor', 15, 2)->default(0);
                $table->date('bom_para')->nullable(); // data de vencimento/deposito
                $table->string('situacao')->default('carteira'); // carteira | depositado | compensado | devolvido | repassado
                $table->foreignId('motivo_devolucao_id')->nullable()->constrained('motivos_devolucao_cheque')->nullOnDelete();
                $table->text('observacao')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cheques');
    }
};
