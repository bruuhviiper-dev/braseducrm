<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AulaEad extends Model
{
    protected $table = 'aulas_ead';
    protected $fillable = ['modulo_ead_id', 'titulo', 'tipo', 'video_ead_id', 'conteudo', 'ordem'];

    public const TIPOS = ['video' => 'Vídeo', 'texto' => 'Texto', 'questionario' => 'Questionário'];

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(ModuloEad::class, 'modulo_ead_id');
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(VideoEad::class, 'video_ead_id');
    }
}
