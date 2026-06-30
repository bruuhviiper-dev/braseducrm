<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivoCancelamentoMatricula extends Model
{
    protected $table = 'motivos_cancelamento_matricula';

    protected $fillable = ['nome', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];
}
