<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CursoEad extends Model
{
    protected $table = 'cursos_ead';

    protected $fillable = [
        'nome', 'titulo_portal', 'descricao', 'carga_horaria', 'valor', 'imagem', 'ativo',
        'tutor_id', 'agrupador_curso_id', 'sub_agrupador_curso_id',
        'turma_montada_id', 'disciplina_id', 'modelo_documento_id',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'ativo' => 'boolean',
    ];

    public function matriculas(): HasMany
    {
        return $this->hasMany(MatriculaEad::class);
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Profissional::class, 'tutor_id');
    }

    public function agrupador(): BelongsTo
    {
        return $this->belongsTo(AgrupadorCurso::class, 'agrupador_curso_id');
    }

    public function subAgrupador(): BelongsTo
    {
        return $this->belongsTo(SubAgrupadorCurso::class, 'sub_agrupador_curso_id');
    }

    public function turmaMontada(): BelongsTo
    {
        return $this->belongsTo(TurmaMontada::class);
    }

    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function modeloDocumento(): BelongsTo
    {
        return $this->belongsTo(ModeloDocumento::class, 'modelo_documento_id');
    }

    public function modulos(): HasMany
    {
        return $this->hasMany(ModuloEad::class, 'curso_ead_id')->orderBy('ordem');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(TagCursoEad::class, 'curso_ead_tag', 'curso_ead_id', 'tag_curso_ead_id');
    }
}
