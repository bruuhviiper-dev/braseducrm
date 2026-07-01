<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assinatura extends Model
{
    protected $table = 'assinaturas';
    protected $fillable = ['nome', 'cargo', 'ativo'];
    protected $casts = ['ativo' => 'boolean'];
}
