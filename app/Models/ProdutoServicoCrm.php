<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdutoServicoCrm extends Model
{
    protected $table = 'produtos_servicos_crm';

    protected $fillable = ['nome', 'valor', 'descricao', 'ativo'];

    protected $casts = [
        'valor' => 'decimal:2',
        'ativo' => 'boolean',
    ];
}
