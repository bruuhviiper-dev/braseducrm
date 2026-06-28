<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DescontoCondicional extends Model
{
    protected $table = 'descontos_condicionais';

    protected $fillable = ['nome', 'tipo', 'valor', 'dias_antecedencia', 'descricao', 'ativo'];

    protected $casts = [
        'valor' => 'decimal:2',
        'dias_antecedencia' => 'integer',
        'ativo' => 'boolean',
    ];
}
