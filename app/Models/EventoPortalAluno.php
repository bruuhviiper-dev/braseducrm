<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventoPortalAluno extends Model
{
    protected $table = 'eventos_portal_aluno';

    protected $fillable = ['titulo', 'descricao', 'data_inicio', 'data_fim', 'publicado'];

    protected $casts = ['data_inicio' => 'date', 'data_fim' => 'date', 'publicado' => 'boolean'];
}
