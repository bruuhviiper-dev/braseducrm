<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plano_contas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->string('nome');
            $table->foreignId('pai_id')->nullable()->constrained('plano_contas')->nullOnDelete();
            $table->enum('tipo', ['sintetica', 'analitica'])->default('analitica');
            $table->enum('natureza', ['receita', 'despesa'])->default('receita');
            $table->integer('nivel')->default(1);
            $table->integer('ordem')->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('contas_bancarias', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('banco')->nullable();
            $table->string('agencia')->nullable();
            $table->string('conta')->nullable();
            $table->string('tipo_conta')->nullable();
            $table->decimal('saldo_inicial', 15, 2)->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('categorias_receber', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('plano_conta_id')->nullable()->constrained('plano_contas')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('categorias_pagar', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('plano_conta_id')->nullable()->constrained('plano_contas')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('descontos_incondicionais', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->enum('tipo', ['percentual', 'valor'])->default('percentual');
            $table->decimal('valor', 15, 2);
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('descontos_condicionais', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->enum('tipo', ['percentual', 'valor'])->default('percentual');
            $table->decimal('valor', 15, 2);
            $table->integer('dias_antecedencia')->nullable();
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('titulos_receber', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->constrained('pessoas');
            $table->foreignId('matricula_id')->nullable()->constrained('matriculas')->nullOnDelete();
            $table->foreignId('categoria_receber_id')->nullable()->constrained('categorias_receber')->nullOnDelete();
            $table->foreignId('conta_bancaria_id')->nullable()->constrained('contas_bancarias')->nullOnDelete();
            $table->foreignId('desconto_incondicional_id')->nullable()->constrained('descontos_incondicionais')->nullOnDelete();
            $table->string('numero_documento')->nullable();
            $table->decimal('valor_original', 15, 2);
            $table->decimal('valor_desconto', 15, 2)->default(0);
            $table->decimal('valor_acrescimo', 15, 2)->default(0);
            $table->decimal('valor_pago', 15, 2)->default(0);
            $table->date('data_emissao');
            $table->date('data_vencimento');
            $table->date('data_pagamento')->nullable();
            $table->enum('situacao', ['aberto', 'pago', 'cancelado', 'renegociado', 'vencido'])->default('aberto');
            $table->enum('forma_pagamento', ['boleto', 'cartao', 'dinheiro', 'pix', 'cheque', 'transferencia'])->nullable();
            $table->string('nosso_numero')->nullable();
            $table->string('linha_digitavel')->nullable();
            $table->text('observacoes')->nullable();
            $table->foreignId('gerado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('titulos_pagar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->nullable()->constrained('pessoas')->nullOnDelete();
            $table->foreignId('categoria_pagar_id')->nullable()->constrained('categorias_pagar')->nullOnDelete();
            $table->foreignId('conta_bancaria_id')->nullable()->constrained('contas_bancarias')->nullOnDelete();
            $table->string('numero_documento')->nullable();
            $table->string('descricao');
            $table->decimal('valor_original', 15, 2);
            $table->decimal('valor_pago', 15, 2)->default(0);
            $table->date('data_emissao');
            $table->date('data_vencimento');
            $table->date('data_pagamento')->nullable();
            $table->enum('situacao', ['aberto', 'pago', 'cancelado'])->default('aberto');
            $table->text('observacoes')->nullable();
            $table->foreignId('gerado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('lancamentos_financeiros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conta_bancaria_id')->constrained('contas_bancarias');
            $table->foreignId('plano_conta_id')->nullable()->constrained('plano_contas')->nullOnDelete();
            $table->enum('tipo', ['entrada', 'saida', 'transferencia']);
            $table->decimal('valor', 15, 2);
            $table->date('data_lancamento');
            $table->date('data_compensacao')->nullable();
            $table->string('descricao');
            $table->string('documento_referencia')->nullable();
            $table->foreignId('titulo_receber_id')->nullable()->constrained('titulos_receber')->nullOnDelete();
            $table->foreignId('titulo_pagar_id')->nullable()->constrained('titulos_pagar')->nullOnDelete();
            $table->boolean('conciliado')->default(false);
            $table->foreignId('operador_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('caixas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operador_id')->constrained('users');
            $table->foreignId('conta_bancaria_id')->nullable()->constrained('contas_bancarias')->nullOnDelete();
            $table->datetime('data_abertura');
            $table->datetime('data_fechamento')->nullable();
            $table->decimal('valor_abertura', 15, 2)->default(0);
            $table->decimal('valor_fechamento', 15, 2)->nullable();
            $table->enum('situacao', ['aberto', 'fechado', 'encerrado'])->default('aberto');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        Schema::create('movimentacoes_caixa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caixa_id')->constrained('caixas')->cascadeOnDelete();
            $table->enum('tipo', ['entrada', 'saida', 'sangria', 'suprimento']);
            $table->decimal('valor', 15, 2);
            $table->string('descricao');
            $table->enum('forma_pagamento', ['dinheiro', 'cartao_debito', 'cartao_credito', 'pix', 'cheque'])->default('dinheiro');
            $table->foreignId('titulo_receber_id')->nullable()->constrained('titulos_receber')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('renegociacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->constrained('pessoas');
            $table->json('titulos_originais');
            $table->decimal('valor_total_original', 15, 2);
            $table->decimal('valor_total_renegociado', 15, 2);
            $table->integer('numero_parcelas');
            $table->date('data_renegociacao');
            $table->text('observacoes')->nullable();
            $table->foreignId('operador_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('notas_fiscais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->constrained('pessoas');
            $table->foreignId('titulo_receber_id')->nullable()->constrained('titulos_receber')->nullOnDelete();
            $table->string('numero');
            $table->string('serie')->nullable();
            $table->decimal('valor', 15, 2);
            $table->date('data_emissao');
            $table->enum('situacao', ['emitida', 'cancelada'])->default('emitida');
            $table->text('descricao_servico')->nullable();
            $table->timestamps();
        });

        Schema::create('configuracoes_financeiro', function (Blueprint $table) {
            $table->id();
            $table->boolean('boleto_automatico')->default(false);
            $table->boolean('cartao_recorrente')->default(false);
            $table->decimal('multa_atraso', 5, 2)->default(2);
            $table->decimal('juros_dia', 5, 4)->default(0.033);
            $table->json('configuracoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracoes_financeiro');
        Schema::dropIfExists('notas_fiscais');
        Schema::dropIfExists('renegociacoes');
        Schema::dropIfExists('movimentacoes_caixa');
        Schema::dropIfExists('caixas');
        Schema::dropIfExists('lancamentos_financeiros');
        Schema::dropIfExists('titulos_pagar');
        Schema::dropIfExists('titulos_receber');
        Schema::dropIfExists('descontos_condicionais');
        Schema::dropIfExists('descontos_incondicionais');
        Schema::dropIfExists('categorias_pagar');
        Schema::dropIfExists('categorias_receber');
        Schema::dropIfExists('contas_bancarias');
        Schema::dropIfExists('plano_contas');
    }
};
