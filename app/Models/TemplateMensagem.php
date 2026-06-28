<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateMensagem extends Model
{
    protected $table = 'templates_mensagem';

    protected $fillable = [
        'nome', 'tipo', 'canal', 'assunto', 'conteudo', 'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];
}
