<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoProfissional extends Model
{
    protected $table = 'tipos_profissional';

    protected $fillable = ['nome'];
}
