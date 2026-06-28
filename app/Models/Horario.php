<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = 'horarios';

    protected $fillable = [
        'turma_montada_id', 'disciplina_id', 'profissional_id',
        'sala_id', 'dia_semana', 'hora_inicio', 'hora_fim',
    ];

    protected $casts = [
        'dia_semana' => 'integer',
    ];

    public function turmaMontada()
    {
        return $this->belongsTo(TurmaMontada::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function profissional()
    {
        return $this->belongsTo(Profissional::class);
    }

    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }

    public static function diasSemana(): array
    {
        return [
            1 => 'Segunda', 2 => 'Terça', 3 => 'Quarta',
            4 => 'Quinta', 5 => 'Sexta', 6 => 'Sábado', 7 => 'Domingo',
        ];
    }
}
