<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoMaterial extends Model
{
    protected $table = 'tipos_material';
    protected $fillable = ['nome', 'ativo'];
    protected $casts = ['ativo' => 'boolean'];
}
