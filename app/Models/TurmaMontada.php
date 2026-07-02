<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TurmaMontada extends Model
{
    protected $table = 'turmas_montadas';

    protected $fillable = ['turma_id', 'modulo_id', 'periodo_letivo_id', 'sigla', 'nome', 'situacao', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'turma_montada_id');
    }

    /** Contadores por situação (estilo EDUQ: Matriculados / Não confirmados / Concluídos / Cancelados / Total). */
    public function contadores(): array
    {
        $m = $this->matriculas()->get();
        $total = $m->count();
        $matriculados = $m->where('situacao', 'ativa')->count();
        $concluidos = $m->where('situacao', 'concluida')->count();
        $cancelados = $m->whereIn('situacao', ['cancelada', 'evadida'])->count();
        return [
            'matriculados' => $matriculados,
            'nao_confirmados' => max(0, $total - $matriculados - $concluidos - $cancelados),
            'concluidos' => $concluidos,
            'cancelados' => $cancelados,
            'total' => $total,
        ];
    }

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
