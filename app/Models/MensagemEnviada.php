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

    public function enviadoPor()
    {
        return $this->belongsTo(User::class, 'enviado_por');
    }

    /** Rótulo da forma de envio (fiel ao EDUQ). */
    public function getFormaEnvioAttribute(): string
    {
        return ['email' => 'E-mail', 'sms' => 'SMS', 'whatsapp' => 'WhatsApp'][$this->canal] ?? ucfirst((string) $this->canal);
    }
}
