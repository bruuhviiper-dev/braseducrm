<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HoraComplementar extends Model
{
    protected $table = 'horas_complementares';

    protected $fillable = ['matricula_id', 'tipo', 'quantidade', 'situacao', 'descricao'];

    protected $casts = ['quantidade' => 'decimal:2'];

    public const TIPOS = ['Complementar', 'Estágio', 'Extensão'];
    public const SITUACOES = ['Parcial', 'Aprovado'];

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }
}
