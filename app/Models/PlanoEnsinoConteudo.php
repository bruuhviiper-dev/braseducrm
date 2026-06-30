<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanoEnsinoConteudo extends Model
{
    protected $table = 'plano_ensino_conteudos';

    protected $fillable = ['plano_ensino_id', 'topico_plano_id', 'conteudo'];

    public function plano(): BelongsTo
    {
        return $this->belongsTo(PlanoEnsino::class, 'plano_ensino_id');
    }

    public function topico(): BelongsTo
    {
        return $this->belongsTo(TopicoPlano::class, 'topico_plano_id');
    }
}
