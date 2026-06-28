<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrupoOperador extends Model
{
    protected $table = 'grupo_operadores';

    protected $fillable = ['nome', 'descricao', 'ativo'];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function funcoes()
    {
        return $this->belongsToMany(Funcao::class, 'grupo_permissoes');
    }
}
