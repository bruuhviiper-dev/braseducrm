<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PraticaSupervisionada extends Model
{
    protected $table = 'praticas_supervisionadas';

    protected $fillable = ['matricula_id', 'disciplina_id', 'quantidade', 'situacao'];

    protected $casts = ['quantidade' => 'decimal:2'];

    public const SITUACOES = ['Parcial', 'Aprovado'];

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }

    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class);
    }
}
