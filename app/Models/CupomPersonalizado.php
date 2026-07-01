<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CupomPersonalizado extends Model
{
    protected $table = 'cupons_personalizados';
    protected $fillable = ['codigo', 'beneficiario', 'tipo_desconto', 'valor_desconto', 'validade', 'usado', 'ativo'];
    protected $casts = ['validade' => 'date', 'valor_desconto' => 'decimal:2', 'usado' => 'boolean', 'ativo' => 'boolean'];

    public const TIPOS = ['percentual' => 'Percentual (%)', 'valor' => 'Valor fixo (R$)'];
}
