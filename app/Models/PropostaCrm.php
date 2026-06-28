<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropostaCrm extends Model
{
    protected $table = 'propostas_crm';

    protected $fillable = [
        'oportunidade_id', 'titulo', 'valor',
        'descricao', 'situacao', 'data_envio', 'validade',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_envio' => 'date',
        'validade' => 'date',
    ];
}
