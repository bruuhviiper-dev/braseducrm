<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivoDevolucaoCheque extends Model
{
    protected $table = 'motivos_devolucao_cheque';
    protected $fillable = ['nome', 'ativo'];
    protected $casts = ['ativo' => 'boolean'];
}
