<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimentacaoExemplar extends Model
{
    protected $table = 'movimentacoes_exemplar';

    protected $fillable = [
        'exemplar_id', 'pessoa_id', 'data_emprestimo', 'data_prevista_devolucao',
        'data_devolucao', 'multa', 'situacao',
    ];

    protected $casts = [
        'data_emprestimo' => 'date',
        'data_prevista_devolucao' => 'date',
        'data_devolucao' => 'date',
        'multa' => 'decimal:2',
    ];

    public function exemplar(): BelongsTo
    {
        return $this->belongsTo(Exemplar::class);
    }

    public function pessoa(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class);
    }
}
