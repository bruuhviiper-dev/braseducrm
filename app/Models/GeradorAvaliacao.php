<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GeradorAvaliacao extends Model
{
    protected $table = 'geradores_avaliacao';
    protected $fillable = ['descricao'];

    public function parametros(): HasMany
    {
        return $this->hasMany(GeradorAvaliacaoParametro::class);
    }
}
