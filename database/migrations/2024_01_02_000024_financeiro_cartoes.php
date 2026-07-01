<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Contratos de Cartões (70) — define operadora/adquirente e suas taxas
        if (!Schema::hasTable('contratos_cartao')) {
            Schema::create('contratos_cartao', function (Blueprint $table) {
                $table->id();
                $table->string('operadora'); // Cielo, Rede, Stone, GetNet...
                $table->string('descricao')->nullable();
                $table->foreignId('conta_bancaria_id')->nullable()->constrained('contas_bancarias')->nullOnDelete();
                $table->decimal('taxa_debito', 5, 2)->default(0);
                $table->decimal('taxa_credito_vista', 5, 2)->default(0);
                $table->decimal('taxa_credito_parcelado', 5, 2)->default(0);
                $table->integer('prazo_recebimento_dias')->default(30);
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }

        // Conciliação de Recebimentos de Cartão (71)
        if (!Schema::hasTable('recebimentos_cartao')) {
            Schema::create('recebimentos_cartao', function (Blueprint $table) {
                $table->id();
                $table->foreignId('contrato_cartao_id')->constrained('contratos_cartao')->cascadeOnDelete();
                $table->date('data_venda');
                $table->string('bandeira')->nullable();
                $table->string('modalidade')->default('credito_vista'); // debito | credito_vista | credito_parcelado
                $table->integer('parcelas')->default(1);
                $table->decimal('valor_bruto', 15, 2)->default(0);
                $table->decimal('taxa_aplicada', 5, 2)->default(0);
                $table->decimal('valor_liquido', 15, 2)->default(0);
                $table->date('previsao_recebimento')->nullable();
                $table->boolean('conciliado')->default(false);
                $table->date('data_conciliacao')->nullable();
                $table->timestamps();
            });
        }

        // Cartão de Crédito Empresarial (136)
        if (!Schema::hasTable('cartoes_empresariais')) {
            Schema::create('cartoes_empresariais', function (Blueprint $table) {
                $table->id();
                $table->string('nome'); // apelido do cartão
                $table->string('bandeira')->nullable();
                $table->string('ultimos_digitos', 4)->nullable();
                $table->foreignId('banco_id')->nullable()->constrained('bancos')->nullOnDelete();
                $table->decimal('limite', 15, 2)->default(0);
                $table->integer('dia_fechamento')->nullable();
                $table->integer('dia_vencimento')->nullable();
                $table->boolean('ativo')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cartoes_empresariais');
        Schema::dropIfExists('recebimentos_cartao');
        Schema::dropIfExists('contratos_cartao');
    }
};
