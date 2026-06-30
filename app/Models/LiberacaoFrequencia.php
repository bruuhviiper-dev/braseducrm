<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiberacaoFrequencia extends Model
{
    protected $table = 'liberacoes_frequencia';

    protected $fillable = ['turma_montada_id', 'profissional_id', 'data_inicio', 'data_fim'];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    public function turmaMontada(): BelongsTo
    {
        return $this->belongsTo(TurmaMontada::class, 'turma_montada_id');
    }

    public function profissional(): BelongsTo
    {
        return $this->belongsTo(Profissional::class);
    }
}
