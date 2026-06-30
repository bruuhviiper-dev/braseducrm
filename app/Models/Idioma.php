<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Idioma extends Model
{
    protected $table = 'idiomas';
    protected $fillable = ['nome', 'ativo'];
    protected $casts = ['ativo' => 'boolean'];
}
