<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RespostaQuestionario extends Model
{
    protected $table = 'respostas_questionario';

    protected $fillable = ['questionario_id', 'respondente_nome', 'respondente_email'];

    public function questionario()
    {
        return $this->belongsTo(Questionario::class);
    }

    public function respostas()
    {
        return $this->hasMany(RespostaQuestao::class, 'resposta_questionario_id');
    }
}
