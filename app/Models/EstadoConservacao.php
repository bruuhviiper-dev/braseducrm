<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoConservacao extends Model
{
    protected $table = 'estados_conservacao';
    protected $fillable = ['nome', 'ativo'];
    protected $casts = ['ativo' => 'boolean'];
}
