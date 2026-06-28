<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaEstoque extends Model
{
    protected $table = 'categorias_estoque';
    protected $fillable = ['nome'];
}
