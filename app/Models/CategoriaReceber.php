<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaReceber extends Model
{
    protected $table = 'categorias_receber';

    protected $fillable = ['nome', 'plano_conta_id'];

    public function planoConta()
    {
        return $this->belongsTo(PlanoContas::class, 'plano_conta_id');
    }
}
