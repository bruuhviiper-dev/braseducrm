<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Obra extends Model
{
    protected $table = 'obras';

    protected $fillable = [
        'isbn', 'titulo', 'subtitulo', 'editor_id', 'area_conhecimento_id',
        'idioma_id', 'tipo_material_id', 'colecao_id', 'capa',
    ];

    public function editor(): BelongsTo
    {
        return $this->belongsTo(Editor::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(AreaConhecimento::class, 'area_conhecimento_id');
    }

    public function idioma(): BelongsTo
    {
        return $this->belongsTo(Idioma::class);
    }

    public function tipoMaterial(): BelongsTo
    {
        return $this->belongsTo(TipoMaterial::class);
    }

    public function autores(): BelongsToMany
    {
        return $this->belongsToMany(Autor::class, 'obra_autor')->withTimestamps();
    }

    public function exemplares(): HasMany
    {
        return $this->hasMany(Exemplar::class);
    }
}
