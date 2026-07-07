<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pendências finais dos docs do EDUQ: histórico escolar manual (migração de alunos antigos),
 * ação automática Ganho→Pós-Vendas executável, roleta de leads, fórmulas condicionais
 * de boletim (REC/Média Final) e réguas de cobrança.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Histórico escolar manual (Ecrã 23 → aba Notas e Faltas → Histórico Escolar)
        Schema::create('historico_escolar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
            $table->foreignId('disciplina_id')->constrained('disciplinas');
            $table->foreignId('modulo_id')->nullable()->constrained('modulos')->nullOnDelete();
            $table->decimal('media', 5, 2)->nullable();
            $table->string('status', 20)->default('aprovado'); // aprovado | dispensado | reprovado | cursando
            $table->string('observacao')->nullable();
            $table->timestamps();
        });

        // Ação automática (256): destino da duplicação Ganho → funil de Pós-Vendas
        Schema::table('acoes_automaticas_crm', function (Blueprint $table) {
            $table->foreignId('funil_destino_id')->nullable()->constrained('funis')->nullOnDelete();
            $table->foreignId('responsavel_destino_id')->nullable()->constrained('users')->nullOnDelete();
        });

        // Roleta do CRM: operadores participantes com proporção (A recebe 3, B recebe 2...)
        Schema::create('roleta_operadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('proporcao')->default(1);
            $table->integer('ordem')->default(0);
            $table->integer('leads_ciclo')->default(0); // contador dentro do ciclo atual
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
        Schema::table('configuracoes_crm', function (Blueprint $table) {
            $table->integer('minutos_estagnacao')->nullable(); // redistribuir lead parado (ex.: 20)
            $table->boolean('considerar_dias_uteis')->default(true);
        });

        // Fórmulas condicionais do boletim (modelos Graduação/Pós/Livres dos docs)
        Schema::table('configuracoes_boletim', function (Blueprint $table) {
            $table->string('modelo', 30)->default('direto'); // direto | recuperacao_media | recuperacao_substitui
            $table->decimal('rec_min', 5, 2)->default(0);
            $table->decimal('rec_max', 5, 2)->default(5.99);
            $table->decimal('media_aprovacao_final', 5, 2)->nullable(); // pós-REC (graduação: 5)
        });
        Schema::table('tabela_avaliacao_itens', function (Blueprint $table) {
            $table->boolean('recuperacao')->default(false);
        });

        // Réguas de cobrança (59): faixas de antecedência/atraso + aviso de pagamento
        Schema::create('reguas_cobranca', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 20); // antecedencia | atraso | pagamento
            $table->integer('dias')->default(0);
            $table->string('canal', 20)->default('email');
            $table->text('mensagem')->nullable();
            $table->boolean('filtrar_ja_notificados')->default(true);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
        Schema::create('regua_envios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regua_cobranca_id')->constrained('reguas_cobranca')->cascadeOnDelete();
            $table->foreignId('titulo_receber_id')->constrained('titulos_receber')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regua_envios');
        Schema::dropIfExists('reguas_cobranca');
        Schema::table('tabela_avaliacao_itens', fn (Blueprint $t) => $t->dropColumn('recuperacao'));
        Schema::table('configuracoes_boletim', fn (Blueprint $t) => $t->dropColumn(['modelo', 'rec_min', 'rec_max', 'media_aprovacao_final']));
        Schema::table('configuracoes_crm', fn (Blueprint $t) => $t->dropColumn(['minutos_estagnacao', 'considerar_dias_uteis']));
        Schema::dropIfExists('roleta_operadores');
        Schema::table('acoes_automaticas_crm', fn (Blueprint $t) => $t->dropColumn(['funil_destino_id', 'responsavel_destino_id']));
        Schema::dropIfExists('historico_escolar');
    }
};
