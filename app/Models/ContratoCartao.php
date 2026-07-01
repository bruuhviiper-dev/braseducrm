<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContratoCartao extends Model
{
    protected $table = 'contratos_cartao';

    protected $fillable = [
        'operadora', 'descricao', 'conta_bancaria_id',
        'taxa_debito', 'taxa_credito_vista', 'taxa_credito_parcelado',
        'prazo_recebimento_dias', 'ativo',
    ];

    protected $casts = [
        'taxa_debito' => 'decimal:2',
        'taxa_credito_vista' => 'decimal:2',
        'taxa_credito_parcelado' => 'decimal:2',
        'ativo' => 'boolean',
    ];

    public function contaBancaria(): BelongsTo
    {
        return $this->belongsTo(ContaBancaria::class);
    }

    public function recebimentos(): HasMany
    {
        return $this->hasMany(RecebimentoCartao::class);
    }

    /** Taxa (%) correspondente à modalidade informada. */
    public function taxaPara(string $modalidade): float
    {
        return (float) match ($modalidade) {
            'debito' => $this->taxa_debito,
            'credito_parcelado' => $this->taxa_credito_parcelado,
            default => $this->taxa_credito_vista,
        };
    }
}
