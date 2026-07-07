<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivoFinalizacaoAtividade extends Model
{
    protected $table = 'motivos_finalizacao_atividade';

    protected $fillable = ['nome'];
}
