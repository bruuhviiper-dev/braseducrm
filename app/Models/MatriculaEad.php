<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatriculaEad extends Model
{
    protected $table = 'matriculas_ead';

    protected $fillable = [
        'aluno_id', 'curso_ead_id', 'data_matricula', 'progresso', 'situacao',
        'ativo', 'permitir_inadimplente',
    ];

    protected $casts = [
        'data_matricula' => 'date',
        'progresso' => 'decimal:2',
        'ativo' => 'boolean',
        'permitir_inadimplente' => 'boolean',
    ];

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    public function cursoEad(): BelongsTo
    {
        return $this->belongsTo(CursoEad::class, 'curso_ead_id');
    }
}
