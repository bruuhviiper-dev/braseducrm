<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContatoInteressado extends Model
{
    protected $table = 'contatos_interessado';
    protected $fillable = ['interessado_id', 'nome', 'telefone', 'email'];

    public function interessado(): BelongsTo
    {
        return $this->belongsTo(Interessado::class);
    }
}
