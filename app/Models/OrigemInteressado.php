<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrigemInteressado extends Model
{
    protected $table = 'origens_interessado';

    protected $fillable = ['nome', 'ativo'];

    protected $casts = [
        'ativo' => 'boolean',
    ];
}
