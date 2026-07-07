<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Regras de negócio capturadas dos documentos de treinamento do EDUQ
 * (acadêmico, financeiro, requerimentos, atendimento, CRM, matrícula online).
 */
return new class extends Migration
{
    public function up(): void
    {
        // Matriz Curricular (30): tipo de vínculo da disciplina e flag 100% EAD
        Schema::table('matriz_disciplinas', function (Blueprint $table) {
            $table->string('tipo_vinculo', 20)->default('obrigatoria'); // obrigatoria | optativa | nao_obrigatoria
            $table->boolean('ead')->default(false);
        });
        // migra o booleano antigo para o novo vocabulário
        DB::table('matriz_disciplinas')->where('obrigatoria', false)->update(['tipo_vinculo' => 'nao_obrigatoria']);

        // Turma Montada (41): vigência real do período letivo
        Schema::table('turmas_montadas', function (Blueprint $table) {
            $table->date('data_inicio')->nullable();
            $table->date('data_fim')->nullable();
        });

        // Títulos a Receber (64): baixa manual com juros/multa e pagador real
        Schema::table('titulos_receber', function (Blueprint $table) {
            $table->string('pagador')->nullable();
            $table->decimal('valor_juros', 10, 2)->nullable();
            $table->decimal('valor_multa', 10, 2)->nullable();
        });

        // Requerimentos (94): filtros, bloqueios, cota de isenção e automações
        Schema::table('tipos_requerimento', function (Blueprint $table) {
            $table->boolean('isento')->default(true);
            $table->integer('vencimento_dias')->nullable();
            $table->integer('cota_isencao')->nullable();
            $table->boolean('exigir_anexo')->default(false);
            $table->boolean('bloquear_inadimplente')->default(false);
            $table->boolean('bloquear_parcelas_abertas')->default(false);
            $table->boolean('ocultar_portal')->default(false);
            $table->boolean('exigir_aprovacao')->default(false);
            $table->boolean('finalizar_apos_pagamento')->default(false);
            $table->boolean('cancelar_sem_pagamento')->default(false);
            $table->string('novo_status_matricula', 30)->nullable(); // trancada | desistente | cancelada
            $table->foreignId('departamento_id')->nullable()->constrained('departamentos')->nullOnDelete();
            $table->foreignId('categoria_receber_id')->nullable()->constrained('categorias_receber')->nullOnDelete();
            $table->foreignId('conta_bancaria_id')->nullable()->constrained('contas_bancarias')->nullOnDelete();
        });

        // Atendimento (55): encerramento com objetivo alcançado + retorno agendado
        Schema::table('atendimentos', function (Blueprint $table) {
            $table->boolean('objetivo_alcancado')->nullable();
            $table->date('data_retorno')->nullable();
        });

        // Categorias de Atendimento (54): vínculo com departamento interno
        Schema::table('categorias_atendimento', function (Blueprint $table) {
            $table->foreignId('departamento_id')->nullable()->constrained('departamentos')->nullOnDelete();
        });

        // Cupons (182): incidência (matrícula/mensalidades/ambas) e consultor exclusivo
        Schema::table('cupons_desconto', function (Blueprint $table) {
            $table->string('incidencia', 20)->default('ambas'); // matricula | mensalidades | ambas
            $table->foreignId('consultor_id')->nullable()->constrained('users')->nullOnDelete();
        });

        // Motivos de falha do atendimento (178): seeds padrão do EDUQ
        if (DB::table('motivos_falha_atendimento')->count() === 0) {
            $now = now();
            DB::table('motivos_falha_atendimento')->insert(array_map(fn ($n) => [
                'nome' => $n, 'created_at' => $now, 'updated_at' => $now,
            ], [
                'Instituição não atende ao requisito',
                'Aluno não retornou o contato',
                'Solicitação fora do prazo',
                'Documentação incompleta',
            ]));
        }
    }

    public function down(): void
    {
        Schema::table('matriz_disciplinas', function (Blueprint $table) {
            $table->dropColumn(['tipo_vinculo', 'ead']);
        });
        Schema::table('turmas_montadas', function (Blueprint $table) {
            $table->dropColumn(['data_inicio', 'data_fim']);
        });
        Schema::table('titulos_receber', function (Blueprint $table) {
            $table->dropColumn(['pagador', 'valor_juros', 'valor_multa']);
        });
        Schema::table('tipos_requerimento', function (Blueprint $table) {
            $table->dropColumn([
                'isento', 'vencimento_dias', 'cota_isencao', 'exigir_anexo', 'bloquear_inadimplente',
                'bloquear_parcelas_abertas', 'ocultar_portal', 'exigir_aprovacao', 'finalizar_apos_pagamento',
                'cancelar_sem_pagamento', 'novo_status_matricula', 'departamento_id', 'categoria_receber_id', 'conta_bancaria_id',
            ]);
        });
        Schema::table('atendimentos', function (Blueprint $table) {
            $table->dropColumn(['objetivo_alcancado', 'data_retorno']);
        });
        Schema::table('categorias_atendimento', function (Blueprint $table) {
            $table->dropColumn('departamento_id');
        });
        Schema::table('cupons_desconto', function (Blueprint $table) {
            $table->dropColumn(['incidencia', 'consultor_id']);
        });
    }
};
