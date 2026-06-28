<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketMensagem extends Model
{
    protected $table = 'ticket_mensagens';

    protected $fillable = [
        'ticket_id', 'user_id', 'mensagem',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
