<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivoRestricao extends Model
{
    protected $table = 'motivos_restricao';

    protected $fillable = ['nome'];
}
