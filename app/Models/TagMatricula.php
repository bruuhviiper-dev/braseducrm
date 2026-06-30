<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagMatricula extends Model
{
    protected $table = 'tags_matricula';

    protected $fillable = ['nome', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];
}
