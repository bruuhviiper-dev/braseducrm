<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResponsavelAluno extends Model
{
    protected $table = 'responsaveis_aluno';
    protected $fillable = ['aluno_id', 'nome', 'parentesco', 'cpf', 'telefone', 'email', 'principal'];
    protected $casts = ['principal' => 'boolean'];

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }
}
