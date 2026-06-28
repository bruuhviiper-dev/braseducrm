<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoGed extends Model
{
    protected $table = 'documentos_ged';

    protected $fillable = [
        'classificacao_ged_id', 'titulo', 'arquivo',
        'tipo_arquivo', 'observacoes', 'enviado_por',
    ];

    public function classificacao()
    {
        return $this->belongsTo(ClassificacaoGed::class, 'classificacao_ged_id');
    }
}
