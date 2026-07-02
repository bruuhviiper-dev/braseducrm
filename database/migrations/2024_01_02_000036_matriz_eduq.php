<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matrizes_curriculares', function (Blueprint $table) {
            $add = [
                'ativo' => fn () => $table->boolean('ativo')->default(true)->after('situacao'),
                'inicio_vigencia' => fn () => $table->date('inicio_vigencia')->nullable()->after('ativo'),
                'sigla' => fn () => $table->string('sigla')->nullable()->after('nome'),
                'area_conhecimento_id' => fn () => $table->foreignId('area_conhecimento_id')->nullable()->after('curso_id')->constrained('areas_conhecimento')->nullOnDelete(),
                'grau_id' => fn () => $table->foreignId('grau_id')->nullable()->after('area_conhecimento_id')->constrained('graus')->nullOnDelete(),
                'habilitacao_id' => fn () => $table->foreignId('habilitacao_id')->nullable()->after('grau_id')->constrained('habilitacoes')->nullOnDelete(),
                // selects reais do EDUQ (Informações Básicas)
                'configuracao_boletim_id' => fn () => $table->unsignedBigInteger('configuracao_boletim_id')->nullable()->after('habilitacao_id'),
                'tabela_avaliacao_id' => fn () => $table->unsignedBigInteger('tabela_avaliacao_id')->nullable()->after('configuracao_boletim_id'),
                'estrutura_plano_aula_id' => fn () => $table->unsignedBigInteger('estrutura_plano_aula_id')->nullable()->after('tabela_avaliacao_id'),
                'estrutura_plano_ensino_id' => fn () => $table->unsignedBigInteger('estrutura_plano_ensino_id')->nullable()->after('estrutura_plano_aula_id'),
                'carga_horaria_descritiva' => fn () => $table->string('carga_horaria_descritiva')->nullable()->after('carga_horaria_total'),
                'anotacoes' => fn () => $table->text('anotacoes')->nullable()->after('observacoes'),
                // configurações (Módulos)
                'matricular_todas' => fn () => $table->boolean('matricular_todas')->default(false)->after('anotacoes'),
                'permite_duplicadas' => fn () => $table->boolean('permite_duplicadas')->default(false)->after('matricular_todas'),
                'percentual_frequencia' => fn () => $table->integer('percentual_frequencia')->nullable()->default(75)->after('permite_duplicadas'),
                'sistema_curricular' => fn () => $table->string('sistema_curricular')->nullable()->after('percentual_frequencia'),
                // configurações acadêmicas (Informações Básicas)
                'controla_horas_compl' => fn () => $table->boolean('controla_horas_compl')->default(false)->after('sistema_curricular'),
                'horas_compl' => fn () => $table->integer('horas_compl')->nullable()->after('controla_horas_compl'),
                'horas_compl_min' => fn () => $table->integer('horas_compl_min')->nullable()->after('horas_compl'),
                'controla_extensao' => fn () => $table->boolean('controla_extensao')->default(false)->after('horas_compl_min'),
                'controla_estagio' => fn () => $table->boolean('controla_estagio')->default(false)->after('controla_extensao'),
                'historico_parcial_portal' => fn () => $table->boolean('historico_parcial_portal')->default(false)->after('controla_estagio'),
            ];
            foreach ($add as $col => $fn) {
                if (!Schema::hasColumn('matrizes_curriculares', $col)) {
                    $fn();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('matrizes_curriculares', function (Blueprint $table) {
            foreach (['area_conhecimento_id', 'grau_id', 'habilitacao_id'] as $fk) {
                if (Schema::hasColumn('matrizes_curriculares', $fk)) {
                    $table->dropConstrainedForeignKey($fk);
                }
            }
            foreach ([
                'historico_parcial_portal', 'controla_estagio', 'controla_extensao', 'horas_compl_min', 'horas_compl',
                'controla_horas_compl', 'sistema_curricular', 'percentual_frequencia', 'permite_duplicadas', 'matricular_todas',
                'anotacoes', 'carga_horaria_descritiva',
                'estrutura_plano_ensino_id', 'estrutura_plano_aula_id', 'tabela_avaliacao_id', 'configuracao_boletim_id',
                'inicio_vigencia', 'ativo', 'sigla',
            ] as $col) {
                if (Schema::hasColumn('matrizes_curriculares', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
