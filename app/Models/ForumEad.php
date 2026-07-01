<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumEad extends Model
{
    protected $table = 'foruns_ead';
    protected $fillable = ['titulo', 'curso_ead_id'];

    public function cursoEad(): BelongsTo
    {
        return $this->belongsTo(CursoEad::class, 'curso_ead_id');
    }

    public function mensagens(): HasMany
    {
        return $this->hasMany(ForumMensagem::class)->orderBy('created_at');
    }
}
