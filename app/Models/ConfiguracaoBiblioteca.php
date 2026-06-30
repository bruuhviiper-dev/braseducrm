<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoBiblioteca extends Model
{
    protected $table = 'configuracoes_biblioteca';

    protected $fillable = [
        'max_emprestimos', 'dias_devolucao', 'max_renovacoes', 'dias_reserva', 'max_reservas',
        'aplicar_multa', 'valor_diario', 'categoria_titulo', 'forma_pagamento',
    ];

    protected $casts = [
        'aplicar_multa' => 'boolean',
        'valor_diario' => 'decimal:2',
    ];

    /** Singleton de configuração. */
    public static function current(): self
    {
        return static::firstOrCreate([]);
    }
}
