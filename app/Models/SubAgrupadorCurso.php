<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubAgrupadorCurso extends Model
{
    protected $table = 'sub_agrupadores_curso';
    protected $fillable = ['nome', 'agrupador_curso_id'];

    public function agrupador(): BelongsTo
    {
        return $this->belongsTo(AgrupadorCurso::class, 'agrupador_curso_id');
    }
}
