<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradeHorario extends Model
{
    protected $table = 'grades_horario';

    protected $fillable = [
        'nome', 'turno_id', 'ativo', 'hora_inicio', 'hora_fim', 'duracao_aula_minutos',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function turno(): BelongsTo
    {
        return $this->belongsTo(Turno::class);
    }

    public function aulas(): HasMany
    {
        return $this->hasMany(GradeHorarioAula::class)->orderBy('ordem');
    }
}
