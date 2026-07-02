<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('titulos_pagar', function (Blueprint $table) {
            if (!Schema::hasColumn('titulos_pagar', 'forma_pagamento')) {
                $table->string('forma_pagamento')->nullable()->after('categoria_pagar_id');
            }
            if (!Schema::hasColumn('titulos_pagar', 'plano_conta_id')) {
                $table->foreignId('plano_conta_id')->nullable()->after('forma_pagamento')->constrained('plano_contas')->nullOnDelete();
            }
            if (!Schema::hasColumn('titulos_pagar', 'referencia')) {
                $table->string('referencia')->nullable()->after('data_vencimento');
            }
            if (!Schema::hasColumn('titulos_pagar', 'linha_digitavel')) {
                $table->string('linha_digitavel')->nullable()->after('numero_documento');
            }
        });

        // Rateio por Centro de Custo (aba do EDUQ)
        if (!Schema::hasTable('rateios_centro_custo')) {
            Schema::create('rateios_centro_custo', function (Blueprint $table) {
                $table->id();
                $table->foreignId('titulo_pagar_id')->constrained('titulos_pagar')->cascadeOnDelete();
                $table->foreignId('centro_custo_id')->constrained('centros_custo')->cascadeOnDelete();
                $table->decimal('valor', 10, 2)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('rateios_centro_custo');
        Schema::table('titulos_pagar', function (Blueprint $table) {
            if (Schema::hasColumn('titulos_pagar', 'plano_conta_id')) {
                $table->dropConstrainedForeignKey('plano_conta_id');
            }
            foreach (['linha_digitavel', 'referencia', 'forma_pagamento'] as $col) {
                if (Schema::hasColumn('titulos_pagar', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
