<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeHorarioAula extends Model
{
    protected $table = 'grade_horario_aulas';

    protected $fillable = [
        'grade_horario_id', 'hora_inicio', 'hora_fim', 'hora_aula', 'ordem',
    ];

    public function grade(): BelongsTo
    {
        return $this->belongsTo(GradeHorario::class, 'grade_horario_id');
    }
}
