<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frequencia extends Model
{
    protected $table = 'frequencias';

    protected $fillable = [
        'matricula_id', 'disciplina_id', 'horario_id',
        'data', 'status', 'conteudo_ministrado', 'lancado_por',
    ];

    protected $casts = [
        'data' => 'date',
    ];

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }
}
