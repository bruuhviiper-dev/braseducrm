<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlternativaQuestao extends Model
{
    protected $table = 'alternativas_questao';

    protected $fillable = ['questao_avulsa_id', 'texto', 'correta', 'ordem'];

    protected $casts = ['correta' => 'boolean'];

    public function questao(): BelongsTo
    {
        return $this->belongsTo(QuestaoAvulsa::class, 'questao_avulsa_id');
    }
}
