<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdutoEstoque extends Model
{
    protected $table = 'produtos_estoque';

    protected $fillable = [
        'nome', 'codigo', 'categoria_estoque_id', 'unidade_medida_id',
        'preco_custo', 'estoque_minimo', 'estoque_atual', 'ativo',
    ];

    protected $casts = [
        'preco_custo' => 'decimal:2',
        'ativo' => 'boolean',
    ];

    public function categoriaEstoque()
    {
        return $this->belongsTo(CategoriaEstoque::class);
    }

    public function unidadeMedida()
    {
        return $this->belongsTo(UnidadeMedida::class);
    }
}
