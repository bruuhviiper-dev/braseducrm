<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopicoPlano extends Model
{
    protected $table = 'topicos_plano';

    protected $fillable = ['nome', 'obrigatoria'];

    protected $casts = ['obrigatoria' => 'boolean'];
}
