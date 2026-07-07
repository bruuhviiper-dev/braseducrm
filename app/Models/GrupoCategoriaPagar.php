<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoCategoriaPagar extends Model
{
    protected $table = 'grupos_categoria_pagar';

    protected $fillable = ['nome'];
}
