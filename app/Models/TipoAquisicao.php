<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoAquisicao extends Model
{
    protected $table = 'tipos_aquisicao';
    protected $fillable = ['nome', 'ativo'];
    protected $casts = ['ativo' => 'boolean'];
}
