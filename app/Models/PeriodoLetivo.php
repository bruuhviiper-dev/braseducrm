<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodoLetivo extends Model
{
    protected $table = 'periodos_letivos';

    protected $fillable = ['nome', 'data_inicio', 'data_fim', 'ativo'];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'ativo' => 'boolean',
    ];
}
