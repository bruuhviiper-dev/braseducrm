<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaPagar extends Model
{
    protected $table = 'categorias_pagar';

    protected $fillable = ['nome', 'plano_conta_id', 'grupo', 'ativo'];

    public function planoConta()
    {
        return $this->belongsTo(PlanoContas::class, 'plano_conta_id');
    }
}
