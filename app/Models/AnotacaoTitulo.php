<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Aba Anotações da ficha do Título 64 (doc revisão): comentários internos da secretaria. */
class AnotacaoTitulo extends Model
{
    protected $table = 'anotacoes_titulo';

    protected $fillable = ['titulo_receber_id', 'user_id', 'texto'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function titulo()
    {
        return $this->belongsTo(TituloReceber::class, 'titulo_receber_id');
    }
}
