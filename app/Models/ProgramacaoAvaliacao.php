<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramacaoAvaliacao extends Model
{
    protected $table = 'programacoes_avaliacao';

    protected $fillable = ['turma_montada_id', 'disciplina_id', 'tabela_avaliacao_id', 'data_avaliacao'];

    protected $casts = ['data_avaliacao' => 'date'];

    public function turmaMontada(): BelongsTo
    {
        return $this->belongsTo(TurmaMontada::class, 'turma_montada_id');
    }

    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function tabelaAvaliacao(): BelongsTo
    {
        return $this->belongsTo(TabelaAvaliacao::class);
    }
}
