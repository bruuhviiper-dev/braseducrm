<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentoMatricula extends Model
{
    protected $table = 'documentos_matricula';
    protected $fillable = ['matricula_id', 'documento', 'entregue', 'observacao'];
    protected $casts = ['entregue' => 'boolean'];

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }
}
