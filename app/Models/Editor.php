<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Editor extends Model
{
    protected $table = 'editores';
    protected $fillable = ['nome', 'ativo'];
    protected $casts = ['ativo' => 'boolean'];
}
