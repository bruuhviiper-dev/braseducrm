<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivoPausa extends Model
{
    protected $table = 'motivos_pausa';

    protected $fillable = ['nome'];
}
