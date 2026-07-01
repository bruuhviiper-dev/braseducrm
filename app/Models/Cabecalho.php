<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabecalho extends Model
{
    protected $table = 'cabecalhos';
    protected $fillable = ['nome', 'conteudo', 'ativo'];
    protected $casts = ['ativo' => 'boolean'];
}
