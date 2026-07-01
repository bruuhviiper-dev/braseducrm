<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeradorAvaliacaoParametro extends Model
{
    protected $table = 'gerador_avaliacao_parametros';
    protected $fillable = ['gerador_avaliacao_id', 'tag_questao_id', 'quantidade'];

    public function gerador(): BelongsTo
    {
        return $this->belongsTo(GeradorAvaliacao::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(TagQuestao::class, 'tag_questao_id');
    }
}
