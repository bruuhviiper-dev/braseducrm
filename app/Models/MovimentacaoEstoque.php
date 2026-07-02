<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimentacaoEstoque extends Model
{
    protected $table = 'movimentacoes_estoque';

    protected $fillable = [
        'produto_estoque_id', 'deposito_id', 'deposito_origem_id', 'deposito_destino_id', 'tipo', 'data_movimentacao',
        'quantidade', 'valor_unitario', 'motivo', 'operador_id',
    ];

    protected $casts = [
        'quantidade' => 'decimal:2',
        'valor_unitario' => 'decimal:2',
        'data_movimentacao' => 'date',
    ];

    public function produtoEstoque()
    {
        return $this->belongsTo(ProdutoEstoque::class);
    }

    public function deposito()
    {
        return $this->belongsTo(Deposito::class);
    }

    public function depositoOrigem()
    {
        return $this->belongsTo(Deposito::class, 'deposito_origem_id');
    }

    public function depositoDestino()
    {
        return $this->belongsTo(Deposito::class, 'deposito_destino_id');
    }

    /** Total da movimentação (quantidade × valor unitário). */
    public function getTotalAttribute(): float
    {
        return (float) $this->quantidade * (float) $this->valor_unitario;
    }

    public function operador()
    {
        return $this->belongsTo(User::class, 'operador_id');
    }
}
