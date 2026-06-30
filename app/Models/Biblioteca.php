<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Biblioteca extends Model
{
    protected $table = 'bibliotecas';
    protected $fillable = ['nome', 'ativo'];
    protected $casts = ['ativo' => 'boolean'];
}
