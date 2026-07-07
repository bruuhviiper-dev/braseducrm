<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InteracaoAtendimento extends Model
{
    protected $table = 'interacoes_atendimento';

    protected $fillable = ['atendimento_id', 'user_id', 'mensagem', 'interna'];

    protected $casts = ['interna' => 'boolean'];

    public function atendimento()
    {
        return $this->belongsTo(Atendimento::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
