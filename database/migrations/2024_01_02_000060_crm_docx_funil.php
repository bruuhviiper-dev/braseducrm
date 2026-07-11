<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * CRM conforme doc "crm (1).docx" (regra de negócio própria da BrasEdu):
 * linha do tempo do card (anotações/anexos/disparos/movimentações), interesses
 * múltiplos, link de matrícula online com expiração, qualificação por estrelas,
 * e campos financeiros do wizard de matrícula (plano de conta por título,
 * instruções de boleto e trava de juros/multa).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historicos_oportunidade', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oportunidade_id')->constrained('oportunidades')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tipo', 20)->default('anotacao'); // anotacao|anexo|atendimento|atividade|disparo|movimentacao
            $table->text('texto')->nullable();
            $table->string('arquivo')->nullable();
            $table->timestamps();
        });

        Schema::create('interesses_oportunidade', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oportunidade_id')->constrained('oportunidades')->cascadeOnDelete();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('links_matricula_online', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oportunidade_id')->constrained('oportunidades')->cascadeOnDelete();
            $table->unsignedBigInteger('abertura_matricula_id')->nullable();
            $table->string('token', 64)->unique();
            $table->boolean('novo_checkout')->default(true);
            $table->dateTime('expira_em')->nullable();
            $table->timestamps();
        });

        Schema::table('oportunidades', function (Blueprint $table) {
            $table->unsignedTinyInteger('estrelas')->default(0); // qualificação 0-5 (doc: termômetro do lead)
            $table->string('midia')->nullable();
        });

        Schema::table('titulos_receber', function (Blueprint $table) {
            $table->unsignedBigInteger('plano_conta_id')->nullable();
            $table->string('instrucoes_boleto', 250)->nullable();
            $table->boolean('cobrar_juros_multa')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historicos_oportunidade');
        Schema::dropIfExists('interesses_oportunidade');
        Schema::dropIfExists('links_matricula_online');
        Schema::table('oportunidades', function (Blueprint $table) {
            $table->dropColumn(['estrelas', 'midia']);
        });
        Schema::table('titulos_receber', function (Blueprint $table) {
            $table->dropColumn(['plano_conta_id', 'instrucoes_boleto', 'cobrar_juros_multa']);
        });
    }
};
