<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormaIngresso extends Model
{
    protected $table = 'formas_ingresso';

    protected $fillable = ['nome'];
}
