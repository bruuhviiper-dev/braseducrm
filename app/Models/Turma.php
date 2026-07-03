<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    protected $fillable = [
        'nome', 'codigo', 'curso_id', 'matriz_curricular_id', 'turno_id',
        'periodo_letivo_id', 'instituicao_ensino_id', 'data_inicio', 'data_fim', 'vagas', 'situacao', 'finalizada', 'comissionavel', 'cor', 'modelo_documento_id', 'conta_id', 'cidade_aulas', 'tipo_turma', 'descricao_horario', 'nao_enviar_contrato',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'finalizada' => 'boolean',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function matrizCurricular()
    {
        return $this->belongsTo(MatrizCurricular::class);
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class);
    }

    public function periodoLetivo()
    {
        return $this->belongsTo(PeriodoLetivo::class);
    }

    public function instituicaoEnsino()
    {
        return $this->belongsTo(InstituicaoEnsino::class);
    }

    public function turmasMontadas()
    {
        return $this->hasMany(TurmaMontada::class);
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }
}
