<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatrizCurricular extends Model
{
    protected $table = 'matrizes_curriculares';

    protected $fillable = ['nome', 'curso_id', 'carga_horaria_total', 'situacao', 'observacoes'];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function disciplinas()
    {
        return $this->belongsToMany(Disciplina::class, 'matriz_disciplinas')
            ->withPivot('modulo_id', 'carga_horaria', 'creditos', 'ordem', 'obrigatoria')
            ->withTimestamps();
    }
}
