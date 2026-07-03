<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('graus', function (Blueprint $t) {
            foreach (['codigo_cnae', 'codigo_servico_lc116', 'codigo_servico_municipal', 'codigo_nbs', 'codigo_tributacao_nacional', 'ibs_cbs_classificacao', 'ibs_cbs_indicador'] as $c) {
                if (!Schema::hasColumn('graus', $c)) $t->string($c)->nullable();
            }
            if (!Schema::hasColumn('graus', 'aliquota_iss')) $t->decimal('aliquota_iss', 8, 2)->nullable();
            if (!Schema::hasColumn('graus', 'nfe_percentual_personalizado')) $t->boolean('nfe_percentual_personalizado')->default(false);
        });
        Schema::table('habilitacoes', function (Blueprint $t) {
            if (!Schema::hasColumn('habilitacoes', 'titulo_conferido')) $t->string('titulo_conferido')->nullable();
        });
        Schema::table('categorias_pagar', function (Blueprint $t) {
            if (!Schema::hasColumn('categorias_pagar', 'grupo')) $t->string('grupo')->nullable();
            if (!Schema::hasColumn('categorias_pagar', 'ativo')) $t->boolean('ativo')->default(true);
        });
        Schema::table('categorias_receber', function (Blueprint $t) {
            if (!Schema::hasColumn('categorias_receber', 'ativo')) $t->boolean('ativo')->default(true);
        });
        Schema::table('contas_bancarias', function (Blueprint $t) {
            foreach (['tesouraria', 'recebimento_caixa', 'ignorar_novos_planos', 'ocultar_saldo_painel', 'desconsiderar_relatorios'] as $c) {
                if (!Schema::hasColumn('contas_bancarias', $c)) $t->boolean($c)->default(false);
            }
            if (!Schema::hasColumn('contas_bancarias', 'eh_conta_bancaria')) $t->boolean('eh_conta_bancaria')->default(true);
            if (!Schema::hasColumn('contas_bancarias', 'instituicao_ensino_id')) $t->foreignId('instituicao_ensino_id')->nullable()->constrained('instituicoes_ensino')->nullOnDelete();
            if (!Schema::hasColumn('contas_bancarias', 'descricao_resumida')) $t->string('descricao_resumida')->nullable();
            if (!Schema::hasColumn('contas_bancarias', 'data_saldo')) $t->date('data_saldo')->nullable();
        });
        Schema::table('descontos_condicionais', function (Blueprint $t) {
            if (!Schema::hasColumn('descontos_condicionais', 'aplicar')) $t->string('aplicar')->nullable();
        });
        if (!Schema::hasTable('desconto_condicional_itens')) {
            Schema::create('desconto_condicional_itens', function (Blueprint $t) {
                $t->id();
                $t->foreignId('desconto_condicional_id')->constrained('descontos_condicionais')->cascadeOnDelete();
                $t->unsignedInteger('dias');
                $t->decimal('valor', 12, 2);
                $t->timestamps();
            });
        }
        if (!Schema::hasTable('bancos')) {
            Schema::create('bancos', function (Blueprint $t) {
                $t->id();
                $t->string('codigo', 10);
                $t->string('nome');
                $t->timestamps();
            });
        }
        if (DB::table('bancos')->count() === 0) {
            $bancos = [
                ['001', 'BANCO DO BRASIL S.A.'],
                ['033', 'BANCO SANTANDER (BRASIL) S.A.'],
                ['041', 'BANCO DO ESTADO DO RIO GRANDE DO SUL S.A.'],
                ['070', 'BRB - BANCO DE BRASILIA S.A.'],
                ['077', 'BANCO INTER S.A.'],
                ['080', 'B&T CORRETORA DE CAMBIO LTDA.'],
                ['102', 'XP INVESTIMENTOS CCTVM S.A.'],
                ['104', 'CAIXA ECONOMICA FEDERAL'],
                ['117', 'ADVANCED CORRETORA DE CÂMBIO LTDA'],
                ['121', 'BANCO AGIBANK S.A.'],
                ['136', 'UNICRED COOPERATIVA'],
                ['188', 'ATIVA INVESTIMENTOS S.A. CORRETORA DE TÍTULOS, CÂMBIO E VALORES'],
                ['197', 'STONE PAGAMENTOS S.A.'],
                ['208', 'BANCO BTG PACTUAL S.A.'],
                ['212', 'BANCO ORIGINAL S.A.'],
                ['218', 'BANCO BS2 S.A.'],
                ['237', 'BANCO BRADESCO S.A.'],
                ['246', 'BANCO ABC BRASIL S.A.'],
                ['260', 'NU PAGAMENTOS S.A. (NUBANK)'],
                ['272', 'AGK CORRETORA DE CAMBIO S.A.'],
                ['290', 'PAGSEGURO INTERNET S.A. (PAGBANK)'],
                ['323', 'MERCADO PAGO IP LTDA.'],
                ['332', 'ACESSO SOLUÇÕES DE PAGAMENTO S.A. - INSTITUIÇÃO DE PAGAMENTO'],
                ['336', 'BANCO C6 S.A.'],
                ['341', 'ITAÚ UNIBANCO S.A.'],
                ['349', 'AL5 S.A. CRÉDITO, FINANCIAMENTO E INVESTIMENTO'],
                ['380', 'PICPAY INSTITUIÇÃO DE PAGAMENTO S.A.'],
                ['389', 'BANCO MERCANTIL DO BRASIL S.A.'],
                ['406', 'ACCREDITO - SOCIEDADE DE CRÉDITO DIRETO S.A.'],
                ['422', 'BANCO SAFRA S.A.'],
                ['461', 'Asaas Gestão Financeira Instituição de Pagamentos S.A.'],
                ['463', 'AZUMI DISTRIBUIDORA DE TÍTULOS E VALORES MOBILIáRIOS LTDA.'],
                ['508', 'AVENUE SECURITIES DISTRIBUIDORA DE TÍTULOS E VALORES MOBILIÁRIOS LTDA.'],
                ['513', 'ATF SOCIEDADE DE CRÉDITO DIRETO S.A.'],
                ['527', 'ATICCA - SOCIEDADE DE CRÉDITO DIRETO S.A.'],
                ['562', 'AZIMUT BRASIL DISTRIBUIDORA DE TÍTULOS E VALORES MOBILIÁRIOS LTDA'],
                ['572', 'ALL IN CRED SOCIEDADE DE CREDITO DIRETO S.A.'],
                ['599', 'AGORACRED S/A SOCIEDADE DE CRÉDITO, FINANCIAMENTO E INVESTIMENTO'],
                ['655', 'BANCO VOTORANTIM S.A.'],
                ['748', 'BANCO COOPERATIVO SICREDI S.A.'],
                ['756', 'BANCO COOPERATIVO SICOOB S.A.'],
            ];
            $now = now();
            DB::table('bancos')->insert(array_map(fn($b) => ['codigo' => $b[0], 'nome' => $b[1], 'created_at' => $now, 'updated_at' => $now], $bancos));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('desconto_condicional_itens');
        Schema::dropIfExists('bancos');
    }
};
