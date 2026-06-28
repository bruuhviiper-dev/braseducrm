<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instituicoes_ensino', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cnpj', 18)->nullable()->unique();
            $table->string('razao_social')->nullable();
            $table->string('endereco')->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf', 2)->nullable();
            $table->string('cep', 10)->nullable();
            $table->string('telefone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('site')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('areas_conhecimento', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('codigo')->nullable();
            $table->timestamps();
        });

        Schema::create('graus', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('habilitacoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('sigla')->nullable();
            $table->foreignId('area_conhecimento_id')->nullable()->constrained('areas_conhecimento')->nullOnDelete();
            $table->foreignId('grau_id')->nullable()->constrained('graus')->nullOnDelete();
            $table->foreignId('habilitacao_id')->nullable()->constrained('habilitacoes')->nullOnDelete();
            $table->foreignId('instituicao_ensino_id')->nullable()->constrained('instituicoes_ensino')->nullOnDelete();
            $table->integer('carga_horaria_total')->nullable();
            $table->integer('duracao_meses')->nullable();
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('disciplinas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('sigla')->nullable();
            $table->integer('carga_horaria')->nullable();
            $table->text('ementa')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('modulos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->integer('ordem')->default(1);
            $table->timestamps();
        });

        Schema::create('matrizes_curriculares', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->integer('carga_horaria_total')->nullable();
            $table->enum('situacao', ['ativa', 'finalizada', 'rascunho'])->default('rascunho');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        Schema::create('matriz_disciplinas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matriz_curricular_id')->constrained('matrizes_curriculares')->cascadeOnDelete();
            $table->foreignId('disciplina_id')->constrained('disciplinas')->cascadeOnDelete();
            $table->foreignId('modulo_id')->nullable()->constrained('modulos')->nullOnDelete();
            $table->integer('carga_horaria')->nullable();
            $table->integer('creditos')->nullable();
            $table->integer('ordem')->default(0);
            $table->boolean('obrigatoria')->default(true);
            $table->timestamps();
        });

        Schema::create('periodos_letivos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('calendarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->integer('ano');
            $table->foreignId('periodo_letivo_id')->nullable()->constrained('periodos_letivos')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('calendario_eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendario_id')->constrained('calendarios')->cascadeOnDelete();
            $table->date('data');
            $table->string('descricao');
            $table->boolean('dia_letivo')->default(true);
            $table->timestamps();
        });

        Schema::create('salas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->integer('capacidade')->nullable();
            $table->string('bloco')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('grades_horario', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('turno_id')->constrained('turnos');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->integer('duracao_aula_minutos')->default(50);
            $table->timestamps();
        });

        Schema::create('turmas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('codigo')->nullable()->unique();
            $table->foreignId('curso_id')->constrained('cursos');
            $table->foreignId('matriz_curricular_id')->constrained('matrizes_curriculares');
            $table->foreignId('turno_id')->constrained('turnos');
            $table->foreignId('periodo_letivo_id')->nullable()->constrained('periodos_letivos')->nullOnDelete();
            $table->foreignId('instituicao_ensino_id')->nullable()->constrained('instituicoes_ensino')->nullOnDelete();
            $table->date('data_inicio')->nullable();
            $table->date('data_fim')->nullable();
            $table->integer('vagas')->nullable();
            $table->enum('situacao', ['aberta', 'em_andamento', 'finalizada', 'cancelada'])->default('aberta');
            $table->timestamps();
        });

        Schema::create('turmas_montadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turma_id')->constrained('turmas')->cascadeOnDelete();
            $table->foreignId('modulo_id')->nullable()->constrained('modulos')->nullOnDelete();
            $table->foreignId('periodo_letivo_id')->nullable()->constrained('periodos_letivos')->nullOnDelete();
            $table->string('nome')->nullable();
            $table->enum('situacao', ['aberta', 'em_andamento', 'finalizada'])->default('aberta');
            $table->timestamps();
        });

        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turma_montada_id')->constrained('turmas_montadas')->cascadeOnDelete();
            $table->foreignId('disciplina_id')->constrained('disciplinas');
            $table->foreignId('profissional_id')->nullable()->constrained('profissionais')->nullOnDelete();
            $table->foreignId('sala_id')->nullable()->constrained('salas')->nullOnDelete();
            $table->tinyInteger('dia_semana');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->timestamps();
        });

        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos');
            $table->foreignId('turma_id')->constrained('turmas');
            $table->foreignId('turma_montada_id')->nullable()->constrained('turmas_montadas')->nullOnDelete();
            $table->string('numero_matricula')->nullable()->unique();
            $table->date('data_matricula');
            $table->enum('situacao', ['ativa', 'trancada', 'cancelada', 'concluida', 'transferida', 'evadida'])->default('ativa');
            $table->foreignId('forma_ingresso_id')->nullable()->constrained('formas_ingresso')->nullOnDelete();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        Schema::create('tabelas_avaliacao', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->decimal('nota_maxima', 5, 2)->default(10);
            $table->decimal('media_aprovacao', 5, 2)->default(7);
            $table->text('descricao')->nullable();
            $table->timestamps();
        });

        Schema::create('tabela_avaliacao_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tabela_avaliacao_id')->constrained('tabelas_avaliacao')->cascadeOnDelete();
            $table->string('nome');
            $table->decimal('peso', 5, 2)->default(1);
            $table->integer('ordem')->default(0);
            $table->timestamps();
        });

        Schema::create('configuracoes_boletim', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('formula')->nullable();
            $table->decimal('media_aprovacao', 5, 2)->default(7);
            $table->decimal('frequencia_minima', 5, 2)->default(75);
            $table->timestamps();
        });

        Schema::create('programacoes_avaliacao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turma_montada_id')->constrained('turmas_montadas')->cascadeOnDelete();
            $table->foreignId('disciplina_id')->constrained('disciplinas');
            $table->foreignId('tabela_avaliacao_id')->constrained('tabelas_avaliacao');
            $table->date('data_avaliacao')->nullable();
            $table->timestamps();
        });

        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
            $table->foreignId('disciplina_id')->constrained('disciplinas');
            $table->foreignId('tabela_avaliacao_item_id')->nullable()->constrained('tabela_avaliacao_itens')->nullOnDelete();
            $table->decimal('nota', 5, 2)->nullable();
            $table->enum('situacao', ['aprovado', 'reprovado', 'cursando', 'pendente'])->default('pendente');
            $table->foreignId('lancado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('frequencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
            $table->foreignId('disciplina_id')->constrained('disciplinas');
            $table->foreignId('horario_id')->nullable()->constrained('horarios')->nullOnDelete();
            $table->date('data');
            $table->enum('status', ['presente', 'ausente', 'justificada'])->default('presente');
            $table->text('conteudo_ministrado')->nullable();
            $table->foreignId('lancado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('conceitos_nota', function (Blueprint $table) {
            $table->id();
            $table->string('conceito', 5);
            $table->decimal('nota_minima', 5, 2);
            $table->decimal('nota_maxima', 5, 2);
            $table->string('descricao')->nullable();
            $table->timestamps();
        });

        Schema::create('configuracoes_academico', function (Blueprint $table) {
            $table->id();
            $table->boolean('assinatura_eletronica')->default(false);
            $table->boolean('envio_email_matricula')->default(false);
            $table->boolean('aniversariante_automatico')->default(false);
            $table->text('email_matricula_template')->nullable();
            $table->json('configuracoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracoes_academico');
        Schema::dropIfExists('conceitos_nota');
        Schema::dropIfExists('frequencias');
        Schema::dropIfExists('notas');
        Schema::dropIfExists('programacoes_avaliacao');
        Schema::dropIfExists('configuracoes_boletim');
        Schema::dropIfExists('tabela_avaliacao_itens');
        Schema::dropIfExists('tabelas_avaliacao');
        Schema::dropIfExists('matriculas');
        Schema::dropIfExists('horarios');
        Schema::dropIfExists('turmas_montadas');
        Schema::dropIfExists('turmas');
        Schema::dropIfExists('grades_horario');
        Schema::dropIfExists('salas');
        Schema::dropIfExists('calendario_eventos');
        Schema::dropIfExists('calendarios');
        Schema::dropIfExists('periodos_letivos');
        Schema::dropIfExists('matriz_disciplinas');
        Schema::dropIfExists('matrizes_curriculares');
        Schema::dropIfExists('modulos');
        Schema::dropIfExists('disciplinas');
        Schema::dropIfExists('cursos');
        Schema::dropIfExists('turnos');
        Schema::dropIfExists('habilitacoes');
        Schema::dropIfExists('graus');
        Schema::dropIfExists('areas_conhecimento');
        Schema::dropIfExists('instituicoes_ensino');
    }
};
