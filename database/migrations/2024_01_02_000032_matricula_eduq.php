<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            // Plano de pagamento (financeiro da matrícula)
            if (!Schema::hasColumn('matriculas', 'valor_total')) {
                $table->decimal('valor_total', 10, 2)->nullable()->after('observacoes');
            }
            if (!Schema::hasColumn('matriculas', 'desconto')) {
                $table->decimal('desconto', 10, 2)->nullable()->after('valor_total');
            }
            if (!Schema::hasColumn('matriculas', 'num_parcelas')) {
                $table->integer('num_parcelas')->nullable()->after('desconto');
            }
            if (!Schema::hasColumn('matriculas', 'valor_parcela')) {
                $table->decimal('valor_parcela', 10, 2)->nullable()->after('num_parcelas');
            }
            if (!Schema::hasColumn('matriculas', 'dia_vencimento')) {
                $table->integer('dia_vencimento')->nullable()->after('valor_parcela');
            }
            if (!Schema::hasColumn('matriculas', 'primeiro_vencimento')) {
                $table->date('primeiro_vencimento')->nullable()->after('dia_vencimento');
            }
            if (!Schema::hasColumn('matriculas', 'forma_pagamento_id')) {
                $table->foreignId('forma_pagamento_id')->nullable()->after('primeiro_vencimento')->constrained('formas_pagamento')->nullOnDelete();
            }
        });

        // Documentos entregues na matrícula (checklist)
        if (!Schema::hasTable('documentos_matricula')) {
            Schema::create('documentos_matricula', function (Blueprint $table) {
                $table->id();
                $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
                $table->string('documento');
                $table->boolean('entregue')->default(false);
                $table->string('observacao')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_matricula');
        Schema::table('matriculas', function (Blueprint $table) {
            if (Schema::hasColumn('matriculas', 'forma_pagamento_id')) {
                $table->dropConstrainedForeignKey('forma_pagamento_id');
            }
            foreach (['primeiro_vencimento', 'dia_vencimento', 'valor_parcela', 'num_parcelas', 'desconto', 'valor_total'] as $col) {
                if (Schema::hasColumn('matriculas', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
