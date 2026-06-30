<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rematricula extends Model
{
    protected $table = 'rematriculas';

    protected $fillable = ['matricula_id', 'futura_turma_id', 'data_abertura', 'situacao'];

    protected $casts = ['data_abertura' => 'date'];

    public const SITUACOES = ['Pendente de assinatura de contrato', 'Confirmada', 'Cancelada'];

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }

    public function futuraTurma(): BelongsTo
    {
        return $this->belongsTo(Turma::class, 'futura_turma_id');
    }
}
