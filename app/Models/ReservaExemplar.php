<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservaExemplar extends Model
{
    protected $table = 'reservas_exemplar';

    protected $fillable = ['biblioteca_id', 'obra_id', 'pessoa_id', 'data_reserva', 'situacao'];

    protected $casts = ['data_reserva' => 'date'];

    public const SITUACOES = ['ativa', 'atendida', 'cancelada'];

    public function biblioteca(): BelongsTo
    {
        return $this->belongsTo(Biblioteca::class);
    }

    public function obra(): BelongsTo
    {
        return $this->belongsTo(Obra::class);
    }

    public function pessoa(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class);
    }
}
