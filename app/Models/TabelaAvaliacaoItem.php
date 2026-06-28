<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TabelaAvaliacaoItem extends Model
{
    protected $table = 'tabela_avaliacao_itens';

    protected $fillable = ['tabela_avaliacao_id', 'nome', 'peso', 'ordem'];

    protected $casts = [
        'peso' => 'decimal:2',
        'ordem' => 'integer',
    ];

    public function tabela()
    {
        return $this->belongsTo(TabelaAvaliacao::class, 'tabela_avaliacao_id');
    }
}
