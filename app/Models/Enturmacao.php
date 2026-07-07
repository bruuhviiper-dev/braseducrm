<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enturmacao extends Model
{
    protected $table = 'enturmacoes';

    protected $fillable = ['matricula_id', 'disciplina_id', 'turma_montada_id', 'data_inicio', 'tipo'];

    protected $casts = ['data_inicio' => 'date'];

    public const TIPOS = ['normal' => 'Normal', 'equivalente' => 'Equivalente', 'optativa' => 'Optativa'];

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function turmaMontada()
    {
        return $this->belongsTo(TurmaMontada::class);
    }
}
