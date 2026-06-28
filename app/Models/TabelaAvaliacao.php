<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TabelaAvaliacao extends Model
{
    protected $table = 'tabelas_avaliacao';

    protected $fillable = ['nome', 'nota_maxima', 'media_aprovacao', 'descricao'];

    protected $casts = [
        'nota_maxima' => 'decimal:2',
        'media_aprovacao' => 'decimal:2',
    ];

    public function itens()
    {
        return $this->hasMany(TabelaAvaliacaoItem::class)->orderBy('ordem');
    }
}
