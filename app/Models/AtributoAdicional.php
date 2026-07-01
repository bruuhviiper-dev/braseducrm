<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtributoAdicional extends Model
{
    protected $table = 'atributos_adicionais';
    protected $fillable = ['nome', 'entidade', 'tipo', 'obrigatorio', 'ativo'];
    protected $casts = ['obrigatorio' => 'boolean', 'ativo' => 'boolean'];

    public const ENTIDADES = ['pessoa' => 'Pessoa', 'aluno' => 'Aluno', 'matricula' => 'Matrícula', 'curso' => 'Curso'];
    public const TIPOS = ['texto' => 'Texto', 'numero' => 'Número', 'data' => 'Data', 'booleano' => 'Sim/Não', 'lista' => 'Lista'];
}
