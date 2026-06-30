<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivoIndisponibilidade extends Model
{
    protected $table = 'motivos_indisponibilidade';
    protected $fillable = ['nome', 'ativo'];
    protected $casts = ['ativo' => 'boolean'];
}
