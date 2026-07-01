<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificacaoAluno extends Model
{
    protected $table = 'notificacoes_aluno';
    protected $fillable = ['aluno_id', 'titulo', 'mensagem', 'tipo', 'para_todos', 'lida'];
    protected $casts = ['para_todos' => 'boolean', 'lida' => 'boolean'];

    public const TIPOS = ['info' => 'Informativo', 'aviso' => 'Aviso', 'sucesso' => 'Sucesso', 'urgente' => 'Urgente'];

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }
}
