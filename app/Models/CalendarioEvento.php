<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarioEvento extends Model
{
    protected $table = 'calendario_eventos';

    protected $fillable = ['calendario_id', 'data', 'descricao', 'dia_letivo'];

    protected $casts = [
        'data' => 'date',
        'dia_letivo' => 'boolean',
    ];

    public function calendario(): BelongsTo
    {
        return $this->belongsTo(Calendario::class);
    }
}
