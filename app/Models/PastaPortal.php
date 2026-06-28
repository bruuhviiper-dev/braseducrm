<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PastaPortal extends Model
{
    protected $table = 'pastas_portal';

    protected $fillable = ['nome', 'descricao', 'ordem', 'ativo'];

    protected $casts = [
        'ordem' => 'integer',
        'ativo' => 'boolean',
    ];

    public function publicacoes()
    {
        return $this->hasMany(PublicacaoPortal::class, 'pasta_portal_id');
    }
}
