<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Opcao extends Model
{
    protected $table = 'opcoes';

    protected $fillable = ['questao_id', 'texto', 'ordem', 'valor'];

    protected $casts = [
        'ordem' => 'integer',
        'valor' => 'decimal:2',
    ];

    public function questao()
    {
        return $this->belongsTo(Questao::class);
    }
}
