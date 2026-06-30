<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $simples = [
            'bibliotecas', 'colecoes', 'editores', 'estados_conservacao',
            'idiomas', 'tipos_aquisicao', 'tipos_material', 'motivos_indisponibilidade',
        ];
        foreach ($simples as $tabela) {
            if (!Schema::hasTable($tabela)) {
                Schema::create($tabela, function (Blueprint $table) {
                    $table->id();
                    $table->string('nome');
                    $table->boolean('ativo')->default(true);
                    $table->timestamps();
                });
            }
        }

        if (!Schema::hasTable('autores')) {
            Schema::create('autores', function (Blueprint $table) {
                $table->id();
                $table->string('nome');
                $table->string('sobrenome')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('obras')) {
            Schema::create('obras', function (Blueprint $table) {
                $table->id();
                $table->string('isbn')->nullable();
                $table->string('titulo');
                $table->string('subtitulo')->nullable();
                $table->foreignId('editor_id')->nullable()->constrained('editores')->nullOnDelete();
                $table->foreignId('area_conhecimento_id')->nullable()->constrained('areas_conhecimento')->nullOnDelete();
                $table->foreignId('idioma_id')->nullable()->constrained('idiomas')->nullOnDelete();
                $table->foreignId('tipo_material_id')->nullable()->constrained('tipos_material')->nullOnDelete();
                $table->foreignId('colecao_id')->nullable()->constrained('colecoes')->nullOnDelete();
                $table->string('capa')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('obra_autor')) {
            Schema::create('obra_autor', function (Blueprint $table) {
                $table->id();
                $table->foreignId('obra_id')->constrained('obras')->cascadeOnDelete();
                $table->foreignId('autor_id')->constrained('autores')->cascadeOnDelete();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('exemplares')) {
            Schema::create('exemplares', function (Blueprint $table) {
                $table->id();
                $table->foreignId('obra_id')->constrained('obras')->cascadeOnDelete();
                $table->foreignId('biblioteca_id')->constrained('bibliotecas');
                $table->string('codigo')->nullable(); // tombo
                $table->foreignId('estado_conservacao_id')->nullable()->constrained('estados_conservacao')->nullOnDelete();
                $table->foreignId('doador_pessoa_id')->nullable()->constrained('pessoas')->nullOnDelete();
                $table->foreignId('tipo_aquisicao_id')->nullable()->constrained('tipos_aquisicao')->nullOnDelete();
                $table->decimal('valor_compra', 10, 2)->nullable();
                $table->date('data_aquisicao')->nullable();
                $table->boolean('copia_local')->default(false);
                $table->string('situacao')->default('disponivel'); // disponivel, emprestado, reservado, indisponivel
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('movimentacoes_exemplar')) {
            Schema::create('movimentacoes_exemplar', function (Blueprint $table) {
                $table->id();
                $table->foreignId('exemplar_id')->constrained('exemplares')->cascadeOnDelete();
                $table->foreignId('pessoa_id')->constrained('pessoas');
                $table->date('data_emprestimo');
                $table->date('data_prevista_devolucao');
                $table->date('data_devolucao')->nullable();
                $table->decimal('multa', 10, 2)->default(0);
                $table->string('situacao')->default('emprestado'); // emprestado, devolvido
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('reservas_exemplar')) {
            Schema::create('reservas_exemplar', function (Blueprint $table) {
                $table->id();
                $table->foreignId('biblioteca_id')->constrained('bibliotecas');
                $table->foreignId('obra_id')->constrained('obras')->cascadeOnDelete();
                $table->foreignId('pessoa_id')->constrained('pessoas');
                $table->date('data_reserva');
                $table->string('situacao')->default('ativa'); // ativa, atendida, cancelada
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('configuracoes_biblioteca')) {
            Schema::create('configuracoes_biblioteca', function (Blueprint $table) {
                $table->id();
                $table->integer('max_emprestimos')->default(5);
                $table->integer('dias_devolucao')->default(7);
                $table->integer('max_renovacoes')->default(2);
                $table->integer('dias_reserva')->default(0);
                $table->integer('max_reservas')->default(0);
                $table->boolean('aplicar_multa')->default(false);
                $table->decimal('valor_diario', 10, 2)->default(0);
                $table->string('categoria_titulo')->nullable();
                $table->string('forma_pagamento')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        foreach ([
            'configuracoes_biblioteca', 'reservas_exemplar', 'movimentacoes_exemplar',
            'exemplares', 'obra_autor', 'obras', 'autores',
            'motivos_indisponibilidade', 'tipos_material', 'tipos_aquisicao', 'idiomas',
            'estados_conservacao', 'editores', 'colecoes', 'bibliotecas',
        ] as $tabela) {
            Schema::dropIfExists($tabela);
        }
    }
};
