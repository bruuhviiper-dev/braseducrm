<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DescontoIncondicional extends Model
{
    protected $table = 'descontos_incondicionais';

    protected $fillable = ['nome', 'tipo', 'valor', 'descricao', 'ativo'];

    protected $casts = [
        'valor' => 'decimal:2',
        'ativo' => 'boolean',
    ];
}
