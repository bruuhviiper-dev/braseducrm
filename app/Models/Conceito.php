<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conceito extends Model
{
    protected $table = 'conceitos_nota';

    protected $fillable = ['conceito', 'nota_minima', 'nota_maxima', 'descricao'];

    protected $casts = [
        'nota_minima' => 'decimal:2',
        'nota_maxima' => 'decimal:2',
    ];
}
