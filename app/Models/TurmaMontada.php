<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TurmaMontada extends Model
{
    protected $table = 'turmas_montadas';

    protected $fillable = ['turma_id', 'modulo_id', 'periodo_letivo_id', 'nome', 'situacao'];

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    public function periodoLetivo()
    {
        return $this->belongsTo(PeriodoLetivo::class);
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class, 'turma_montada_id');
    }
}
