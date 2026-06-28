<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AberturaMatriculaOnline extends Model
{
    protected $table = 'aberturas_matricula_online';

    protected $fillable = [
        'nome', 'curso_id', 'data_inicio', 'data_fim',
        'valor_matricula', 'valor_curso', 'vagas', 'descricao', 'ativo',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'valor_matricula' => 'decimal:2',
        'valor_curso' => 'decimal:2',
        'vagas' => 'integer',
        'ativo' => 'boolean',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function inscricoes()
    {
        return $this->hasMany(Inscricao::class, 'abertura_matricula_id');
    }

    public function cupons()
    {
        return $this->hasMany(CupomDesconto::class, 'abertura_matricula_id');
    }
}
