<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    protected $fillable = [
        'pessoa_id', 'ra', 'forma_ingresso_id', 'titularidade_id', 'data_ingresso',
        'informacoes_adicionais', 'tipo_sanguineo', 'alergia_id', 'necessidade_especial_id',
        'observacoes_saude', 'ativo',
    ];

    protected $casts = [
        'data_ingresso' => 'date',
        'ativo' => 'boolean',
    ];

    public const TIPOS_SANGUINEOS = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function formaIngresso()
    {
        return $this->belongsTo(FormaIngresso::class);
    }

    public function titularidade()
    {
        return $this->belongsTo(Titularidade::class);
    }

    public function alergia()
    {
        return $this->belongsTo(Alergia::class);
    }

    public function necessidadeEspecial()
    {
        return $this->belongsTo(NecessidadeEspecial::class);
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }

    public function responsaveis()
    {
        return $this->hasMany(ResponsavelAluno::class);
    }

    public function formacoes()
    {
        return $this->hasMany(FormacaoAluno::class);
    }
}
