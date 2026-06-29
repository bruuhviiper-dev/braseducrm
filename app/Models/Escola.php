<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escola extends Model
{
    protected $fillable = ['nome', 'telefone', 'cidade', 'uf', 'tipo_escola'];

    /** Tipos de escola conforme EDUQ (função 8). */
    public const TIPOS = [
        'Privada',
        'Pública Estadual',
        'Pública Municipal',
        'Pública Federal',
        'Conveniada',
    ];
}
