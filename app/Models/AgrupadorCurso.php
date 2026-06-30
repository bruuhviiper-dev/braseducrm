<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgrupadorCurso extends Model
{
    protected $table = 'agrupadores_curso';
    protected $fillable = ['nome'];
}
