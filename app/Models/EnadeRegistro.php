<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnadeRegistro extends Model
{
    protected $table = 'enade_registros';

    protected $fillable = ['matricula_id', 'edicao', 'situacao', 'observacao'];

    public const SITUACOES = ['ingressante' => 'Ingressante', 'concluinte' => 'Concluinte', 'dispensado' => 'Dispensado'];
}
