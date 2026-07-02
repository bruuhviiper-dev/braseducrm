<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interessados', function (Blueprint $table) {
            $add = [
                'e_empresa' => fn () => $table->boolean('e_empresa')->default(false)->after('nome'),
                'nao_enviar_mensagens' => fn () => $table->boolean('nao_enviar_mensagens')->default(false)->after('e_empresa'),
                'cpf' => fn () => $table->string('cpf')->nullable()->after('email'),
                'responsavel_id' => fn () => $table->foreignId('responsavel_id')->nullable()->after('origem_id')->constrained('users')->nullOnDelete(),
                'profissao_id' => fn () => $table->foreignId('profissao_id')->nullable()->after('categoria_id')->constrained('profissoes')->nullOnDelete(),
                'cidade' => fn () => $table->string('cidade')->nullable()->after('profissao_id'),
                'formacao' => fn () => $table->string('formacao')->nullable()->after('cidade'),
                'instagram' => fn () => $table->string('instagram')->nullable()->after('formacao'),
                'facebook' => fn () => $table->string('facebook')->nullable()->after('instagram'),
            ];
            foreach ($add as $col => $fn) {
                if (!Schema::hasColumn('interessados', $col)) {
                    $fn();
                }
            }
        });

        if (!Schema::hasTable('contatos_interessado')) {
            Schema::create('contatos_interessado', function (Blueprint $table) {
                $table->id();
                $table->foreignId('interessado_id')->constrained('interessados')->cascadeOnDelete();
                $table->string('nome')->nullable();
                $table->string('telefone')->nullable();
                $table->string('email')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('contatos_interessado');
        Schema::table('interessados', function (Blueprint $table) {
            foreach (['responsavel_id', 'profissao_id'] as $fk) {
                if (Schema::hasColumn('interessados', $fk)) {
                    $table->dropConstrainedForeignKey($fk);
                }
            }
            foreach (['facebook', 'instagram', 'formacao', 'cidade', 'cpf', 'nao_enviar_mensagens', 'e_empresa'] as $col) {
                if (Schema::hasColumn('interessados', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
