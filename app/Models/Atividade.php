<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atividade extends Model
{
    protected $fillable = [
        'user_id', 'titulo', 'descricao', 'data_vencimento',
        'data_conclusao', 'situacao', 'referencia_tipo', 'referencia_id',
    ];

    protected $casts = [
        'data_vencimento' => 'datetime',
        'data_conclusao' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
