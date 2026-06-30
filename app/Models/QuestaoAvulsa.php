<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestaoAvulsa extends Model
{
    protected $table = 'questoes_avulsas';

    protected $fillable = ['ativo', 'titulo', 'enunciado', 'tipo', 'peso', 'tag_questao_id', 'explicacao'];

    protected $casts = [
        'ativo' => 'boolean',
        'peso' => 'decimal:2',
    ];

    public const TIPOS = [
        'multipla_escolha' => 'Múltipla escolha',
        'verdadeiro_falso' => 'Verdadeiro/Falso',
        'dissertativa' => 'Dissertativa',
    ];

    public function tag(): BelongsTo
    {
        return $this->belongsTo(TagQuestao::class, 'tag_questao_id');
    }

    public function alternativas(): HasMany
    {
        return $this->hasMany(AlternativaQuestao::class)->orderBy('ordem');
    }
}
