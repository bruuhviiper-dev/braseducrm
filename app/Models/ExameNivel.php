<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExameNivel extends Model
{
    protected $table = 'exames_nivel';

    protected $fillable = ['aluno_id', 'disciplina_id', 'nota', 'situacao', 'data_exame'];

    protected $casts = [
        'nota' => 'decimal:2',
        'data_exame' => 'date',
    ];

    public const SITUACOES = ['Pendente', 'Aprovado', 'Reprovado'];

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class);
    }
}
