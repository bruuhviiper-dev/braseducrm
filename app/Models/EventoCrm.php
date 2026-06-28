<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventoCrm extends Model
{
    protected $table = 'eventos_crm';

    protected $fillable = ['nome', 'icone', 'cor', 'ativo'];

    protected $casts = [
        'ativo' => 'boolean',
    ];
}
