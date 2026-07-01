<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModuloEad extends Model
{
    protected $table = 'modulos_ead';
    protected $fillable = ['curso_ead_id', 'nome', 'ordem'];

    public function cursoEad(): BelongsTo
    {
        return $this->belongsTo(CursoEad::class, 'curso_ead_id');
    }

    public function aulas(): HasMany
    {
        return $this->hasMany(AulaEad::class, 'modulo_ead_id')->orderBy('ordem');
    }
}
