<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvaliacaoEad extends Model
{
    protected $table = 'avaliacoes_ead';

    protected $fillable = ['curso_ead_id', 'titulo', 'descricao', 'nota_minima', 'tentativas', 'ativo'];

    protected $casts = [
        'nota_minima' => 'decimal:2',
        'ativo' => 'boolean',
    ];

    public function cursoEad(): BelongsTo
    {
        return $this->belongsTo(CursoEad::class, 'curso_ead_id');
    }
}
