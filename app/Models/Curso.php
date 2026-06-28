<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $fillable = [
        'nome', 'sigla', 'area_conhecimento_id', 'grau_id', 'habilitacao_id',
        'instituicao_ensino_id', 'carga_horaria_total', 'duracao_meses', 'descricao', 'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function areaConhecimento()
    {
        return $this->belongsTo(AreaConhecimento::class);
    }

    public function grau()
    {
        return $this->belongsTo(Grau::class);
    }

    public function habilitacao()
    {
        return $this->belongsTo(Habilitacao::class);
    }

    public function instituicaoEnsino()
    {
        return $this->belongsTo(InstituicaoEnsino::class);
    }

    public function turmas()
    {
        return $this->hasMany(Turma::class);
    }

    public function matrizesCurriculares()
    {
        return $this->hasMany(MatrizCurricular::class);
    }
}
