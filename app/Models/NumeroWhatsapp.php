<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NumeroWhatsapp extends Model
{
    protected $table = 'numeros_whatsapp';
    protected $fillable = ['numero', 'descricao', 'principal', 'ativo'];
    protected $casts = ['principal' => 'boolean', 'ativo' => 'boolean'];
}
