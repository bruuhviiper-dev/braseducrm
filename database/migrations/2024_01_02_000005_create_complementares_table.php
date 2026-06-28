<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // COMUNICACAO
        Schema::create('templates_mensagem', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->enum('tipo', ['vencimento', 'cobranca', 'interessados', 'pagamento', 'avulsa']);
            $table->string('canal', 20)->default('email');
            $table->string('assunto')->nullable();
            $table->text('conteudo');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('mensagens_enviadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->nullable()->constrained('pessoas')->nullOnDelete();
            $table->foreignId('template_id')->nullable()->constrained('templates_mensagem')->nullOnDelete();
            $table->string('canal', 20);
            $table->string('destinatario');
            $table->string('assunto')->nullable();
            $table->text('conteudo');
            $table->enum('situacao', ['enviada', 'entregue', 'erro', 'pendente'])->default('pendente');
            $table->text('erro')->nullable();
            $table->foreignId('enviado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // ESTOQUE
        Schema::create('unidades_medida', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('sigla', 10);
            $table->timestamps();
        });

        Schema::create('categorias_estoque', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('depositos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('localizacao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('produtos_estoque', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('codigo')->nullable()->unique();
            $table->foreignId('categoria_estoque_id')->nullable()->constrained('categorias_estoque')->nullOnDelete();
            $table->foreignId('unidade_medida_id')->nullable()->constrained('unidades_medida')->nullOnDelete();
            $table->decimal('preco_custo', 15, 2)->nullable();
            $table->integer('estoque_minimo')->default(0);
            $table->integer('estoque_atual')->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('movimentacoes_estoque', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_estoque_id')->constrained('produtos_estoque');
            $table->foreignId('deposito_id')->nullable()->constrained('depositos')->nullOnDelete();
            $table->enum('tipo', ['entrada', 'saida', 'transferencia']);
            $table->integer('quantidade');
            $table->decimal('valor_unitario', 15, 2)->nullable();
            $table->string('motivo')->nullable();
            $table->foreignId('operador_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // EAD
        Schema::create('cursos_ead', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->integer('carga_horaria')->nullable();
            $table->decimal('valor', 15, 2)->nullable();
            $table->string('imagem')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('agrupadores_curso', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('agrupador_curso_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agrupador_curso_id')->constrained('agrupadores_curso')->cascadeOnDelete();
            $table->foreignId('curso_ead_id')->constrained('cursos_ead')->cascadeOnDelete();
        });

        Schema::create('avaliacoes_ead', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_ead_id')->constrained('cursos_ead')->cascadeOnDelete();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->decimal('nota_minima', 5, 2)->default(7);
            $table->integer('tentativas')->default(1);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('matriculas_ead', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos');
            $table->foreignId('curso_ead_id')->constrained('cursos_ead');
            $table->date('data_matricula');
            $table->decimal('progresso', 5, 2)->default(0);
            $table->enum('situacao', ['ativa', 'concluida', 'cancelada', 'trancada'])->default('ativa');
            $table->timestamps();
        });

        // DOCUMENTOS
        Schema::create('modelos_documento', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->enum('tipo', ['contrato', 'declaracao', 'recibo', 'certificado', 'outro']);
            $table->longText('conteudo');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->boolean('obrigatorio')->default(false);
            $table->foreignId('curso_id')->nullable()->constrained('cursos')->nullOnDelete();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('entregas_documento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matricula_id')->constrained('matriculas')->cascadeOnDelete();
            $table->foreignId('documento_id')->constrained('documentos');
            $table->boolean('entregue')->default(false);
            $table->date('data_entrega')->nullable();
            $table->string('arquivo')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        Schema::create('cabecalhos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('imagem')->nullable();
            $table->text('conteudo_html')->nullable();
            $table->boolean('padrao')->default(false);
            $table->timestamps();
        });

        // REQUERIMENTOS
        Schema::create('tipos_requerimento', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->boolean('cobrado')->default(false);
            $table->decimal('valor', 15, 2)->nullable();
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('motivos_cancelamento_req', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('requerimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos');
            $table->foreignId('tipo_requerimento_id')->constrained('tipos_requerimento');
            $table->foreignId('matricula_id')->nullable()->constrained('matriculas')->nullOnDelete();
            $table->text('descricao')->nullable();
            $table->enum('situacao', ['pendente', 'aprovado', 'reprovado', 'cancelado', 'entregue'])->default('pendente');
            $table->foreignId('motivo_cancelamento_id')->nullable()->constrained('motivos_cancelamento_req')->nullOnDelete();
            $table->text('observacoes')->nullable();
            $table->foreignId('operador_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // ATENDIMENTOS
        Schema::create('categorias_atendimento', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('motivos_falha_atendimento', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('atendimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->constrained('pessoas');
            $table->foreignId('categoria_atendimento_id')->nullable()->constrained('categorias_atendimento')->nullOnDelete();
            $table->foreignId('operador_id')->constrained('users');
            $table->text('descricao');
            $table->enum('situacao', ['aberto', 'em_andamento', 'concluido', 'falha'])->default('aberto');
            $table->foreignId('motivo_falha_id')->nullable()->constrained('motivos_falha_atendimento')->nullOnDelete();
            $table->text('resolucao')->nullable();
            $table->timestamps();
        });

        // QUESTIONARIOS
        Schema::create('tags_questao', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('questoes', function (Blueprint $table) {
            $table->id();
            $table->text('enunciado');
            $table->enum('tipo', ['multipla_escolha', 'dissertativa', 'escala', 'verdadeiro_falso'])->default('multipla_escolha');
            $table->foreignId('tag_questao_id')->nullable()->constrained('tags_questao')->nullOnDelete();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('opcoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questao_id')->constrained('questoes')->cascadeOnDelete();
            $table->string('texto');
            $table->integer('ordem')->default(0);
            $table->decimal('valor', 5, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('questionarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->enum('tipo', ['avaliacao_institucional', 'nps', 'feedback', 'avulso'])->default('avulso');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('questionario_questoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionario_id')->constrained('questionarios')->cascadeOnDelete();
            $table->foreignId('questao_id')->constrained('questoes')->cascadeOnDelete();
            $table->integer('ordem')->default(0);
            $table->boolean('obrigatoria')->default(true);
        });

        // MATRICULA ONLINE
        Schema::create('aberturas_matricula_online', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('curso_id')->nullable()->constrained('cursos')->nullOnDelete();
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->decimal('valor_matricula', 15, 2)->nullable();
            $table->decimal('valor_curso', 15, 2)->nullable();
            $table->integer('vagas')->nullable();
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('tags_matricula_online', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('cupons_desconto', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->enum('tipo', ['percentual', 'valor'])->default('percentual');
            $table->decimal('valor', 15, 2);
            $table->integer('quantidade_total')->nullable();
            $table->integer('quantidade_usada')->default(0);
            $table->date('validade')->nullable();
            $table->foreignId('abertura_matricula_id')->nullable()->constrained('aberturas_matricula_online')->nullOnDelete();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('inscricoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->nullable()->constrained('pessoas')->nullOnDelete();
            $table->foreignId('abertura_matricula_id')->constrained('aberturas_matricula_online');
            $table->string('nome');
            $table->string('email');
            $table->string('telefone', 20)->nullable();
            $table->string('cpf', 14)->nullable();
            $table->enum('situacao', ['pendente', 'aprovada', 'cancelada', 'matriculada'])->default('pendente');
            $table->boolean('pagamento_confirmado')->default(false);
            $table->boolean('contrato_assinado')->default(false);
            $table->foreignId('cupom_desconto_id')->nullable()->constrained('cupons_desconto')->nullOnDelete();
            $table->timestamps();
        });

        // PLANO DE ENSINO/AULA
        Schema::create('topicos_plano', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('estruturas_plano', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->enum('tipo', ['ensino', 'aula'])->default('ensino');
            $table->timestamps();
        });

        Schema::create('estrutura_topicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estrutura_plano_id')->constrained('estruturas_plano')->cascadeOnDelete();
            $table->foreignId('topico_plano_id')->constrained('topicos_plano')->cascadeOnDelete();
            $table->integer('ordem')->default(0);
            $table->boolean('obrigatorio')->default(false);
        });

        // INDICACOES
        Schema::create('campanhas_indicacao', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('banner')->nullable();
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('indicacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos');
            $table->string('nome_indicado');
            $table->string('telefone_indicado', 20)->nullable();
            $table->string('email_indicado')->nullable();
            $table->foreignId('campanha_id')->nullable()->constrained('campanhas_indicacao')->nullOnDelete();
            $table->enum('situacao', ['pendente', 'convertido', 'nao_convertido'])->default('pendente');
            $table->timestamps();
        });

        // ATIVIDADES DO SISTEMA
        Schema::create('atividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->datetime('data_vencimento')->nullable();
            $table->datetime('data_conclusao')->nullable();
            $table->enum('situacao', ['pendente', 'concluida', 'atrasada'])->default('pendente');
            $table->string('referencia_tipo')->nullable();
            $table->unsignedBigInteger('referencia_id')->nullable();
            $table->timestamps();
        });

        Schema::create('novidades', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->date('data_publicacao');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('novidades');
        Schema::dropIfExists('atividades');
        Schema::dropIfExists('indicacoes');
        Schema::dropIfExists('campanhas_indicacao');
        Schema::dropIfExists('estrutura_topicos');
        Schema::dropIfExists('estruturas_plano');
        Schema::dropIfExists('topicos_plano');
        Schema::dropIfExists('inscricoes');
        Schema::dropIfExists('cupons_desconto');
        Schema::dropIfExists('tags_matricula_online');
        Schema::dropIfExists('aberturas_matricula_online');
        Schema::dropIfExists('questionario_questoes');
        Schema::dropIfExists('questionarios');
        Schema::dropIfExists('opcoes');
        Schema::dropIfExists('questoes');
        Schema::dropIfExists('tags_questao');
        Schema::dropIfExists('atendimentos');
        Schema::dropIfExists('motivos_falha_atendimento');
        Schema::dropIfExists('categorias_atendimento');
        Schema::dropIfExists('requerimentos');
        Schema::dropIfExists('motivos_cancelamento_req');
        Schema::dropIfExists('tipos_requerimento');
        Schema::dropIfExists('cabecalhos');
        Schema::dropIfExists('entregas_documento');
        Schema::dropIfExists('documentos');
        Schema::dropIfExists('modelos_documento');
        Schema::dropIfExists('matriculas_ead');
        Schema::dropIfExists('avaliacoes_ead');
        Schema::dropIfExists('agrupador_curso_items');
        Schema::dropIfExists('agrupadores_curso');
        Schema::dropIfExists('cursos_ead');
        Schema::dropIfExists('movimentacoes_estoque');
        Schema::dropIfExists('produtos_estoque');
        Schema::dropIfExists('depositos');
        Schema::dropIfExists('categorias_estoque');
        Schema::dropIfExists('unidades_medida');
        Schema::dropIfExists('mensagens_enviadas');
        Schema::dropIfExists('templates_mensagem');
    }
};
