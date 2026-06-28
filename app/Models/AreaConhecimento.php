<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaConhecimento extends Model
{
    protected $table = 'areas_conhecimento';

    protected $fillable = ['nome', 'codigo'];
}
