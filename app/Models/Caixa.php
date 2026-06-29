<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caixa extends Model
{
    protected $table = 'caixas';

    protected $fillable = [
        'conta_bancaria_id', 'operador_id', 'data_abertura', 'data_fechamento',
        'valor_abertura', 'valor_fechamento', 'situacao', 'observacoes',
    ];

    protected $casts = [
        'data_abertura' => 'datetime',
        'data_fechamento' => 'datetime',
        'valor_abertura' => 'decimal:2',
        'valor_fechamento' => 'decimal:2',
    ];

    public function contaBancaria()
    {
        return $this->belongsTo(ContaBancaria::class);
    }

    public function movimentacoes()
    {
        return $this->hasMany(MovimentacaoCaixa::class);
    }

    /** Saldo = abertura + entradas/suprimentos - saídas/sangrias. */
    public function saldoAtual(): float
    {
        $entradas = $this->movimentacoes()->whereIn('tipo', ['entrada', 'suprimento'])->sum('valor');
        $saidas = $this->movimentacoes()->whereIn('tipo', ['saida', 'sangria'])->sum('valor');
        return (float) $this->valor_abertura + (float) $entradas - (float) $saidas;
    }
}
