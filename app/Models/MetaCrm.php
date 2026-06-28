<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetaCrm extends Model
{
    protected $table = 'metas_crm';

    protected $fillable = [
        'nome', 'funil_id', 'consultor_id', 'tipo',
        'periodo', 'meta_valor', 'data_inicio', 'data_fim',
    ];

    protected $casts = [
        'meta_valor' => 'decimal:2',
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    public function funil()
    {
        return $this->belongsTo(Funil::class);
    }

    public function consultor()
    {
        return $this->belongsTo(User::class, 'consultor_id');
    }
}
