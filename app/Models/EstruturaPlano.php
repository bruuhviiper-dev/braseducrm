<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EstruturaPlano extends Model
{
    protected $table = 'estruturas_plano';

    protected $fillable = ['nome'];

    public function topicos(): BelongsToMany
    {
        return $this->belongsToMany(TopicoPlano::class, 'estrutura_plano_topico', 'estrutura_plano_id', 'topico_plano_id')
            ->withPivot('ordem')
            ->orderBy('estrutura_plano_topico.ordem')
            ->withTimestamps();
    }
}
