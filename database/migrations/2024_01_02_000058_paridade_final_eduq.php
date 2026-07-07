<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Chat do atendimento (ecrã 55 do EDUQ): interações do protocolo, com mensagens
        // internas ocultas ao aluno
        if (!Schema::hasTable('interacoes_atendimento')) {
            Schema::create('interacoes_atendimento', function (Blueprint $table) {
                $table->id();
                $table->foreignId('atendimento_id')->constrained('atendimentos')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->text('mensagem');
                $table->boolean('interna')->default(false);
                $table->timestamps();
            });
        }

        // Alçada de aprovação de propostas (docs do EDUQ): desconto acima do limite do
        // operador exige aprovação do gestor antes de emitir
        if (!Schema::hasColumn('propostas_crm', 'desconto_percentual')) {
            Schema::table('propostas_crm', function (Blueprint $table) {
                $table->decimal('desconto_percentual', 5, 2)->nullable();
                $table->string('aprovacao')->default('nao_requer'); // nao_requer|pendente|aprovada|reprovada
                $table->foreignId('criada_por')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('aprovada_por')->nullable()->constrained('users')->nullOnDelete();
                $table->string('motivo_reprovacao')->nullable();
            });
        }

        if (!Schema::hasColumn('users', 'limite_desconto')) {
            Schema::table('users', function (Blueprint $table) {
                $table->decimal('limite_desconto', 5, 2)->nullable(); // % máx.; null = sem alçada (gestor)
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('interacoes_atendimento');
        Schema::table('propostas_crm', function (Blueprint $table) {
            $table->dropColumn(['desconto_percentual', 'aprovacao', 'criada_por', 'aprovada_por', 'motivo_reprovacao']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('limite_desconto');
        });
    }
};
