<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanoEnsino extends Model
{
    protected $table = 'planos_ensino';

    protected $fillable = ['turma_montada_id', 'disciplina_id', 'estrutura_plano_id', 'ocultar_portal', 'anexo_path'];

    protected $casts = ['ocultar_portal' => 'boolean'];

    public function turmaMontada(): BelongsTo
    {
        return $this->belongsTo(TurmaMontada::class, 'turma_montada_id');
    }

    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function estrutura(): BelongsTo
    {
        return $this->belongsTo(EstruturaPlano::class, 'estrutura_plano_id');
    }

    public function conteudos(): HasMany
    {
        return $this->hasMany(PlanoEnsinoConteudo::class);
    }
}
