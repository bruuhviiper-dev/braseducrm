<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartaoEmpresarial extends Model
{
    protected $table = 'cartoes_empresariais';

    protected $fillable = [
        'nome', 'bandeira', 'ultimos_digitos', 'banco_id',
        'limite', 'dia_fechamento', 'dia_vencimento', 'ativo',
    ];

    protected $casts = [
        'limite' => 'decimal:2',
        'ativo' => 'boolean',
    ];

    public function banco(): BelongsTo
    {
        return $this->belongsTo(Banco::class);
    }
}
