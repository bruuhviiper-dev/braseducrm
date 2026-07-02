<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormacaoAluno extends Model
{
    protected $table = 'formacoes_aluno';
    protected $fillable = ['aluno_id', 'nivel', 'instituicao', 'curso', 'ano_conclusao'];

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }
}
