<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoFinanceiro extends Model
{
    protected $table = 'configuracoes_financeiro';

    protected $fillable = [
        'boleto_automatico', 'cartao_recorrente',
        'multa_atraso', 'juros_dia', 'configuracoes',
    ];

    protected $casts = [
        'boleto_automatico' => 'boolean',
        'cartao_recorrente' => 'boolean',
        'multa_atraso' => 'decimal:2',
        'juros_dia' => 'decimal:4',
        'configuracoes' => 'json',
    ];

    public static function current(): self
    {
        return static::firstOrCreate([]);
    }
}
