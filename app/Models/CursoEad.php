<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CursoEad extends Model
{
    protected $table = 'cursos_ead';

    protected $fillable = [
        'nome', 'descricao', 'carga_horaria', 'valor', 'imagem', 'ativo',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'ativo' => 'boolean',
    ];

    public function matriculas()
    {
        return $this->hasMany(MatriculaEad::class);
    }
}
