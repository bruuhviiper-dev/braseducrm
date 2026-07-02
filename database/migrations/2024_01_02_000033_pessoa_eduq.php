<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pessoas', function (Blueprint $table) {
            $cols = [
                'estrangeiro'          => fn () => $table->boolean('estrangeiro')->default(false)->after('tipo'),
                'passaporte'           => fn () => $table->string('passaporte')->nullable()->after('rg'),
                'origem'               => fn () => $table->string('origem')->nullable()->after('naturalidade'),
                'etnia'                => fn () => $table->string('etnia')->nullable()->after('estado_civil'),
                'nome_pai'             => fn () => $table->string('nome_pai')->nullable()->after('etnia'),
                'nome_mae'             => fn () => $table->string('nome_mae')->nullable()->after('nome_pai'),
                'caixa_postal'         => fn () => $table->string('caixa_postal')->nullable()->after('bairro'),
                'instagram'            => fn () => $table->string('instagram')->nullable()->after('celular'),
                'facebook'             => fn () => $table->string('facebook')->nullable()->after('instagram'),
                'linkedin'             => fn () => $table->string('linkedin')->nullable()->after('facebook'),
                'local_trabalho'       => fn () => $table->string('local_trabalho')->nullable()->after('profissao_id'),
                'numero_conselho'      => fn () => $table->string('numero_conselho')->nullable()->after('local_trabalho'),
                'lattes'               => fn () => $table->string('lattes')->nullable()->after('numero_conselho'),
                'observacoes_saude'    => fn () => $table->text('observacoes_saude')->nullable()->after('observacoes'),
                'nao_receber_mensagens' => fn () => $table->boolean('nao_receber_mensagens')->default(false)->after('observacoes_saude'),
                'blacklist'            => fn () => $table->boolean('blacklist')->default(false)->after('nao_receber_mensagens'),
                'ignorar_reajuste'     => fn () => $table->boolean('ignorar_reajuste')->default(false)->after('blacklist'),
            ];
            foreach ($cols as $name => $add) {
                if (!Schema::hasColumn('pessoas', $name)) {
                    $add();
                }
            }
        });

        if (!Schema::hasTable('telefones_pessoa')) {
            Schema::create('telefones_pessoa', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pessoa_id')->constrained('pessoas')->cascadeOnDelete();
                $table->string('tipo')->nullable();      // celular, residencial, comercial, recado...
                $table->string('numero');
                $table->boolean('whatsapp')->default(false);
                $table->string('observacao')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('contas_pessoa')) {
            Schema::create('contas_pessoa', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pessoa_id')->constrained('pessoas')->cascadeOnDelete();
                $table->string('banco')->nullable();
                $table->string('agencia')->nullable();
                $table->string('conta')->nullable();
                $table->string('tipo')->nullable();       // corrente, poupanca
                $table->string('chave_pix')->nullable();
                $table->string('tipo_pix')->nullable();   // cpf, email, telefone, aleatoria
                $table->string('titular')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('contas_pessoa');
        Schema::dropIfExists('telefones_pessoa');
        Schema::table('pessoas', function (Blueprint $table) {
            foreach ([
                'ignorar_reajuste', 'blacklist', 'nao_receber_mensagens', 'observacoes_saude',
                'lattes', 'numero_conselho', 'local_trabalho', 'linkedin', 'facebook', 'instagram',
                'caixa_postal', 'nome_mae', 'nome_pai', 'etnia', 'origem', 'passaporte', 'estrangeiro',
            ] as $col) {
                if (Schema::hasColumn('pessoas', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
