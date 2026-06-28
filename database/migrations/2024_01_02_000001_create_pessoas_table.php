<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('religioes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('necessidades_especiais', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->timestamps();
        });

        Schema::create('alergias', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('profissoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('tipos_profissional', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('titularidades', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('escolas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cidade')->nullable();
            $table->string('uf', 2)->nullable();
            $table->timestamps();
        });

        Schema::create('formas_ingresso', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });

        Schema::create('pessoas', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['fisica', 'juridica'])->default('fisica');
            $table->string('nome');
            $table->string('nome_social')->nullable();
            $table->string('cpf', 14)->nullable()->unique();
            $table->string('cnpj', 18)->nullable()->unique();
            $table->string('rg', 20)->nullable();
            $table->string('orgao_emissor')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->enum('sexo', ['M', 'F', 'O'])->nullable();
            $table->string('nacionalidade')->nullable();
            $table->string('naturalidade')->nullable();
            $table->string('estado_civil')->nullable();
            $table->string('email')->nullable();
            $table->string('email_secundario')->nullable();
            $table->string('telefone', 20)->nullable();
            $table->string('celular', 20)->nullable();
            $table->string('cep', 10)->nullable();
            $table->string('endereco')->nullable();
            $table->string('numero', 20)->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf', 2)->nullable();
            $table->string('pais')->default('Brasil');
            $table->foreignId('religiao_id')->nullable()->constrained('religioes')->nullOnDelete();
            $table->foreignId('profissao_id')->nullable()->constrained('profissoes')->nullOnDelete();
            $table->foreignId('escola_id')->nullable()->constrained('escolas')->nullOnDelete();
            $table->string('foto')->nullable();
            $table->text('observacoes')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('pessoa_necessidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->constrained()->cascadeOnDelete();
            $table->foreignId('necessidade_especial_id')->constrained('necessidades_especiais')->cascadeOnDelete();
        });

        Schema::create('pessoa_alergias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->constrained()->cascadeOnDelete();
            $table->foreignId('alergia_id')->constrained('alergias')->cascadeOnDelete();
        });

        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->constrained()->cascadeOnDelete();
            $table->string('ra')->nullable()->unique();
            $table->foreignId('forma_ingresso_id')->nullable()->constrained('formas_ingresso')->nullOnDelete();
            $table->date('data_ingresso')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('profissionais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tipo_profissional_id')->nullable()->constrained('tipos_profissional')->nullOnDelete();
            $table->foreignId('titularidade_id')->nullable()->constrained('titularidades')->nullOnDelete();
            $table->string('registro_profissional')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('atributos_adicionais', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('entidade');
            $table->enum('tipo_campo', ['texto', 'numero', 'data', 'selecao', 'textarea'])->default('texto');
            $table->json('opcoes')->nullable();
            $table->boolean('obrigatorio')->default(false);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('atributo_valores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atributo_adicional_id')->constrained('atributos_adicionais')->cascadeOnDelete();
            $table->morphs('atribuivel');
            $table->text('valor')->nullable();
            $table->timestamps();
        });

        Schema::create('assinaturas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cargo');
            $table->string('imagem');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assinaturas');
        Schema::dropIfExists('atributo_valores');
        Schema::dropIfExists('atributos_adicionais');
        Schema::dropIfExists('profissionais');
        Schema::dropIfExists('alunos');
        Schema::dropIfExists('pessoa_alergias');
        Schema::dropIfExists('pessoa_necessidades');
        Schema::dropIfExists('pessoas');
        Schema::dropIfExists('formas_ingresso');
        Schema::dropIfExists('escolas');
        Schema::dropIfExists('titularidades');
        Schema::dropIfExists('tipos_profissional');
        Schema::dropIfExists('profissoes');
        Schema::dropIfExists('alergias');
        Schema::dropIfExists('necessidades_especiais');
        Schema::dropIfExists('religioes');
    }
};
