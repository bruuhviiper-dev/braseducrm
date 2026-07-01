<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampanhaIndicacao extends Model
{
    protected $table = 'campanhas_indicacao';
    protected $fillable = ['nome', 'banner', 'descricao', 'data_inicio', 'data_fim', 'ativo'];
    protected $casts = ['data_inicio' => 'date', 'data_fim' => 'date', 'ativo' => 'boolean'];

    public function indicacoes(): HasMany
    {
        return $this->hasMany(Indicacao::class, 'campanha_id');
    }
}
