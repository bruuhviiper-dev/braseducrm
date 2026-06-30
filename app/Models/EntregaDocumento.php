<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntregaDocumento extends Model
{
    protected $table = 'entregas_documento';

    protected $fillable = ['matricula_id', 'documento_id', 'entregue', 'data_entrega', 'arquivo', 'observacoes'];

    protected $casts = [
        'entregue' => 'boolean',
        'data_entrega' => 'date',
    ];

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }
}
