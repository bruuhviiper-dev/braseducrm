<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MensagemEnviada extends Model
{
    protected $table = 'mensagens_enviadas';

    protected $fillable = [
        'pessoa_id', 'template_id', 'canal', 'destinatario',
        'assunto', 'conteudo', 'situacao', 'erro', 'enviado_por',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function template()
    {
        return $this->belongsTo(TemplateMensagem::class, 'template_id');
    }
}
