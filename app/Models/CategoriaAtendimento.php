<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaAtendimento extends Model
{
    protected $table = 'categorias_atendimento';

    protected $fillable = ['nome'];
}
