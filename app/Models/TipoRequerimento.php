<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoRequerimento extends Model
{
    protected $table = 'tipos_requerimento';

    protected $fillable = ['nome', 'cobrado', 'valor', 'descricao', 'ativo'];

    protected $casts = [
        'cobrado' => 'boolean',
        'valor' => 'decimal:2',
        'ativo' => 'boolean',
    ];
}
