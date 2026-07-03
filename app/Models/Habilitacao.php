<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habilitacao extends Model
{
    protected $table = 'habilitacoes';

    protected $fillable = ['nome', 'titulo_conferido'];
}
