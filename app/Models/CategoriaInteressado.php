<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaInteressado extends Model
{
    protected $table = 'categorias_interessado';

    protected $fillable = ['nome'];
}
