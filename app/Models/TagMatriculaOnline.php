<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagMatriculaOnline extends Model
{
    protected $table = 'tags_matricula_online';
    protected $fillable = ['nome', 'cor', 'ativo'];
    protected $casts = ['ativo' => 'boolean'];
}
