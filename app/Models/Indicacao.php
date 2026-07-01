<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Indicacao extends Model
{
    protected $table = 'indicacoes';
    protected $fillable = [
        'aluno_id', 'nome_indicado', 'telefone_indicado', 'email_indicado',
        'campanha_id', 'situacao',
    ];

    public const STATUS = ['pendente' => 'Pendente', 'convertido' => 'Convertido', 'nao_convertido' => 'Não convertido'];

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    public function campanha(): BelongsTo
    {
        return $this->belongsTo(CampanhaIndicacao::class, 'campanha_id');
    }
}
