<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = 'documentos';

    protected $fillable = ['nome', 'obrigatorio', 'curso_id', 'ativo'];

    protected $casts = [
        'obrigatorio' => 'boolean',
        'ativo' => 'boolean',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
}
