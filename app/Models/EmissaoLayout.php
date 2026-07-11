<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Layout salvo de uma emissão (colunas + filtros), por usuário e função. */
class EmissaoLayout extends Model
{
    protected $table = 'emissao_layouts';

    protected $fillable = ['user_id', 'funcao_codigo', 'nome', 'colunas', 'filtros', 'padrao'];

    protected $casts = ['colunas' => 'array', 'filtros' => 'array', 'padrao' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
