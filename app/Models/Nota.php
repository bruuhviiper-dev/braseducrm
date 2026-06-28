<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $table = 'notas';

    protected $fillable = [
        'matricula_id', 'disciplina_id', 'tabela_avaliacao_item_id',
        'nota', 'situacao', 'lancado_por',
    ];

    protected $casts = [
        'nota' => 'decimal:2',
    ];

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function item()
    {
        return $this->belongsTo(TabelaAvaliacaoItem::class, 'tabela_avaliacao_item_id');
    }
}
