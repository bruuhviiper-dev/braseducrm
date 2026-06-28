<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RespostaQuestao extends Model
{
    protected $table = 'respostas_questao';

    protected $fillable = ['resposta_questionario_id', 'questao_id', 'valor', 'texto'];

    protected $casts = [
        'valor' => 'decimal:2',
    ];

    public function questao()
    {
        return $this->belongsTo(Questao::class);
    }
}
