<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atendimento extends Model
{
    protected $table = 'atendimentos';

    protected $fillable = [
        'pessoa_id', 'categoria_atendimento_id', 'operador_id',
        'descricao', 'situacao', 'motivo_falha_id', 'resolucao', 'responsavel_id', 'canal', 'portal_aluno', 'precisa_retorno', 'departamentos_responsavel',
        'objetivo_alcancado', 'data_retorno',
    ];

    protected $casts = ['objetivo_alcancado' => 'boolean', 'data_retorno' => 'date'];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaAtendimento::class, 'categoria_atendimento_id');
    }

    public function operador()
    {
        return $this->belongsTo(User::class, 'operador_id');
    }

    public function interacoes()
    {
        return $this->hasMany(InteracaoAtendimento::class)->orderBy('id');
    }
}
