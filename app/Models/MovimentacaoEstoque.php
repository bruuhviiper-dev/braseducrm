<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimentacaoEstoque extends Model
{
    protected $table = 'movimentacoes_estoque';

    protected $fillable = [
        'produto_estoque_id', 'deposito_id', 'tipo',
        'quantidade', 'valor_unitario', 'motivo', 'operador_id',
    ];

    protected $casts = [
        'quantidade' => 'decimal:2',
        'valor_unitario' => 'decimal:2',
    ];

    public function produtoEstoque()
    {
        return $this->belongsTo(ProdutoEstoque::class);
    }

    public function deposito()
    {
        return $this->belongsTo(Deposito::class);
    }

    public function operador()
    {
        return $this->belongsTo(User::class, 'operador_id');
    }
}
