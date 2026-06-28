<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposito extends Model
{
    protected $table = 'depositos';

    protected $fillable = ['nome', 'localizacao', 'ativo'];

    protected $casts = [
        'ativo' => 'boolean',
    ];
}
