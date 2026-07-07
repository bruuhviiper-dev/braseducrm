<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoletaOperador extends Model
{
    protected $table = 'roleta_operadores';

    protected $fillable = ['user_id', 'proporcao', 'ordem', 'leads_ciclo', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
