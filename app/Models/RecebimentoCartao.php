<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecebimentoCartao extends Model
{
    protected $table = 'recebimentos_cartao';

    protected $fillable = [
        'contrato_cartao_id', 'data_venda', 'bandeira', 'modalidade', 'parcelas',
        'valor_bruto', 'taxa_aplicada', 'valor_liquido', 'previsao_recebimento',
        'conciliado', 'data_conciliacao',
    ];

    protected $casts = [
        'data_venda' => 'date',
        'previsao_recebimento' => 'date',
        'data_conciliacao' => 'date',
        'valor_bruto' => 'decimal:2',
        'taxa_aplicada' => 'decimal:2',
        'valor_liquido' => 'decimal:2',
        'conciliado' => 'boolean',
    ];

    public const MODALIDADES = [
        'debito' => 'Débito',
        'credito_vista' => 'Crédito à vista',
        'credito_parcelado' => 'Crédito parcelado',
    ];

    public function contrato(): BelongsTo
    {
        return $this->belongsTo(ContratoCartao::class, 'contrato_cartao_id');
    }
}
