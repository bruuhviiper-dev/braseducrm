<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requerimento extends Model
{
    protected $table = 'requerimentos';

    protected $fillable = [
        'aluno_id', 'vinculo_tipo', 'pessoa_id', 'tipo_requerimento_id', 'matricula_id', 'matricula_ead_id',
        'descricao', 'situacao', 'motivo_cancelamento_id',
        'observacoes', 'anotacoes', 'operador_id',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function matriculaEad()
    {
        return $this->belongsTo(MatriculaEad::class);
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
