<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NecessidadeEspecial extends Model
{
    protected $table = 'necessidades_especiais';

    protected $fillable = ['nome', 'descricao'];
}
