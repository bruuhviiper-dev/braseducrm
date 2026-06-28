<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    protected $fillable = [
        'numero_matricula', 'aluno_id', 'turma_id', 'turma_montada_id',
        'data_matricula', 'situacao', 'forma_ingresso_id', 'observacoes',
    ];

    protected $casts = [
        'data_matricula' => 'date',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function turmaMontada()
    {
        return $this->belongsTo(TurmaMontada::class);
    }

    public function formaIngresso()
    {
        return $this->belongsTo(FormaIngresso::class);
    }

    public function notas()
    {
        return $this->hasMany(Nota::class);
    }

    public function frequencias()
    {
        return $this->hasMany(Frequencia::class);
    }
}
