<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContaPessoa extends Model
{
    protected $table = 'contas_pessoa';
    protected $fillable = ['pessoa_id', 'banco', 'agencia', 'conta', 'tipo', 'chave_pix', 'tipo_pix', 'titular'];

    public function pessoa(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class);
    }
}
