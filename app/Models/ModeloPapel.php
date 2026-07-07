<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModeloPapel extends Model
{
    protected $table = 'modelos_papel';

    protected $fillable = ['nome', 'tamanho', 'descricao'];
}
