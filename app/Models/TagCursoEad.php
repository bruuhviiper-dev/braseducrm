<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagCursoEad extends Model
{
    protected $table = 'tags_curso_ead';
    protected $fillable = ['nome', 'ativo'];
    protected $casts = ['ativo' => 'boolean'];
}
