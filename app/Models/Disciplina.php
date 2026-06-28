<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    protected $fillable = ['nome', 'sigla', 'carga_horaria', 'ementa', 'ativo'];

    protected $casts = [
        'ativo' => 'boolean',
    ];
}
