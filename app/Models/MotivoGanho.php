<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivoGanho extends Model
{
    protected $table = 'motivos_ganho';

    protected $fillable = ['nome'];
}
