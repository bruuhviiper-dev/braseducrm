<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CupomDesconto extends Model
{
    protected $table = 'cupons_desconto';

    protected $fillable = [
        'codigo', 'tipo', 'valor', 'quantidade_total',
        'quantidade_usada', 'validade', 'abertura_matricula_id', 'ativo',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'quantidade_total' => 'integer',
        'quantidade_usada' => 'integer',
        'validade' => 'date',
        'ativo' => 'boolean',
    ];

    public function abertura()
    {
        return $this->belongsTo(AberturaMatriculaOnline::class, 'abertura_matricula_id');
    }
}
