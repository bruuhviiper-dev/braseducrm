<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('oportunidades', function (Blueprint $table) {
            if (!Schema::hasColumn('oportunidades', 'origem_id')) {
                $table->foreignId('origem_id')->nullable()->after('interessado_id')->constrained('origens_interessado')->nullOnDelete();
            }
            if (!Schema::hasColumn('oportunidades', 'indicacao_id')) {
                $table->foreignId('indicacao_id')->nullable()->after('origem_id')->constrained('indicacoes')->nullOnDelete();
            }
            if (!Schema::hasColumn('oportunidades', 'qualificacao')) {
                $table->string('qualificacao')->nullable()->after('situacao'); // quente|morno|frio
            }
            if (!Schema::hasColumn('oportunidades', 'motivacao_interesse')) {
                $table->text('motivacao_interesse')->nullable()->after('observacoes');
            }
        });

        if (!Schema::hasTable('oportunidade_tags')) {
            Schema::create('oportunidade_tags', function (Blueprint $table) {
                $table->id();
                $table->foreignId('oportunidade_id')->constrained('oportunidades')->cascadeOnDelete();
                $table->foreignId('tag_crm_id')->constrained('tags_crm')->cascadeOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('oportunidade_tags');
        Schema::table('oportunidades', function (Blueprint $table) {
            foreach (['origem_id', 'indicacao_id'] as $fk) {
                if (Schema::hasColumn('oportunidades', $fk)) {
                    $table->dropConstrainedForeignKey($fk);
                }
            }
            foreach (['motivacao_interesse', 'qualificacao'] as $col) {
                if (Schema::hasColumn('oportunidades', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
