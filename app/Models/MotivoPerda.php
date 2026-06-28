<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivoPerda extends Model
{
    protected $table = 'motivos_perda';

    protected $fillable = ['nome'];
}
