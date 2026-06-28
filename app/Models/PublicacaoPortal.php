<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicacaoPortal extends Model
{
    protected $table = 'publicacoes_portal';

    protected $fillable = [
        'pasta_portal_id', 'titulo', 'conteudo', 'arquivo',
        'publicado_em', 'ativo', 'publicado_por',
    ];

    protected $casts = [
        'publicado_em' => 'date',
        'ativo' => 'boolean',
    ];

    public function pasta()
    {
        return $this->belongsTo(PastaPortal::class, 'pasta_portal_id');
    }
}
