<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimentacaoCaixa extends Model
{
    protected $table = 'movimentacoes_caixa';

    protected $fillable = [
        'caixa_id', 'tipo', 'valor', 'descricao',
        'forma_pagamento', 'titulo_receber_id',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
    ];

    public function caixa()
    {
        return $this->belongsTo(Caixa::class);
    }
}
