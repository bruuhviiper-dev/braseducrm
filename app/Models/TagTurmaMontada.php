<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagTurmaMontada extends Model
{
    protected $table = 'tags_turma_montada';

    protected $fillable = ['nome', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];
}
