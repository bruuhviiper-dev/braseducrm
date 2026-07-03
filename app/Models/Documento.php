<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = 'documentos';

    protected $fillable = ['nome', 'obrigatorio', 'curso_id', 'ativo', 'sigla', 'tipo_ged', 'idade_minima', 'visibilidade_matriz', 'obrigatorio_generos', 'grau', 'forma_ingresso_id'];

    protected $casts = [
        'obrigatorio' => 'boolean',
        'ativo' => 'boolean',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
}
