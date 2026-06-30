<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colecao extends Model
{
    protected $table = 'colecoes';
    protected $fillable = ['nome', 'ativo'];
    protected $casts = ['ativo' => 'boolean'];
}
