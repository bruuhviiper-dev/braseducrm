<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requerimento extends Model
{
    protected $table = 'requerimentos';

    protected $fillable = [
        'aluno_id', 'tipo_requerimento_id', 'matricula_id',
        'descricao', 'situacao', 'motivo_cancelamento_id',
        'observacoes', 'operador_id',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function tipoRequerimento()
    {
        return $this->belongsTo(TipoRequerimento::class);
    }

    public function operador()
    {
        return $this->belongsTo(User::class, 'operador_id');
    }
}
