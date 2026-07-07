<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivoFalhaAtendimento extends Model
{
    protected $table = 'motivos_falha_atendimento';

    protected $fillable = ['nome'];
}
