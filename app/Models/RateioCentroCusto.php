<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RateioCentroCusto extends Model
{
    protected $table = 'rateios_centro_custo';
    protected $fillable = ['titulo_pagar_id', 'centro_custo_id', 'valor'];
    protected $casts = ['valor' => 'decimal:2'];

    public function tituloPagar(): BelongsTo
    {
        return $this->belongsTo(TituloPagar::class);
    }

    public function centroCusto(): BelongsTo
    {
        return $this->belongsTo(CentroCusto::class);
    }
}
