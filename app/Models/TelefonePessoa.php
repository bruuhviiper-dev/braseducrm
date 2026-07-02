<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelefonePessoa extends Model
{
    protected $table = 'telefones_pessoa';
    protected $fillable = ['pessoa_id', 'tipo', 'numero', 'whatsapp', 'observacao'];
    protected $casts = ['whatsapp' => 'boolean'];

    public function pessoa(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class);
    }
}
