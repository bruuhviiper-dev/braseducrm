<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Renegociacao extends Model
{
    protected $table = 'renegociacoes';

    protected $fillable = [
        'pessoa_id', 'titulos_originais', 'valor_total_original',
        'valor_total_renegociado', 'numero_parcelas', 'data_renegociacao',
        'observacoes', 'operador_id',
    ];

    protected $casts = [
        'titulos_originais' => 'array',
        'valor_total_original' => 'decimal:2',
        'valor_total_renegociado' => 'decimal:2',
        'numero_parcelas' => 'integer',
        'data_renegociacao' => 'date',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
}
