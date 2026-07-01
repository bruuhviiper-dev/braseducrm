<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    protected $table = 'bancos';
    protected $fillable = ['codigo', 'nome', 'ativo'];
    protected $casts = ['ativo' => 'boolean'];
}
