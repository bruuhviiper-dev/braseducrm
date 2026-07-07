<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoBoletim extends Model
{
    protected $table = 'configuracoes_boletim';

    protected $fillable = ['nome', 'formula', 'media_aprovacao', 'frequencia_minima', 'modelo', 'rec_min', 'rec_max', 'media_aprovacao_final'];

    protected $casts = [
        'media_aprovacao' => 'decimal:2',
        'frequencia_minima' => 'decimal:2',
    ];
}
