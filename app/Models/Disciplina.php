<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    protected $fillable = ['nome', 'sigla', 'carga_horaria', 'ementa', 'estrutura_plano_ensino_id', 'ativo'];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    /** Matrizes curriculares que incluem esta disciplina. */
    public function matrizes()
    {
        return $this->belongsToMany(MatrizCurricular::class, 'matriz_disciplinas')
            ->withPivot('modulo_id', 'carga_horaria', 'obrigatoria');
    }
}
