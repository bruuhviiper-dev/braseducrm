<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReguaEnvio extends Model
{
    protected $table = 'regua_envios';

    protected $fillable = ['regua_cobranca_id', 'titulo_receber_id'];
}
