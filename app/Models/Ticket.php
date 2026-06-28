<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id', 'assunto', 'descricao', 'prioridade', 'situacao',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mensagens()
    {
        return $this->hasMany(TicketMensagem::class)->orderBy('created_at');
    }
}
