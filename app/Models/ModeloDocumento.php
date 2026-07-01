<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModeloDocumento extends Model
{
    protected $table = 'modelos_documento';
    protected $fillable = ['nome', 'tipo', 'conteudo', 'ativo'];
    protected $casts = ['ativo' => 'boolean'];

    public const TIPOS = [
        'contrato' => 'Contrato',
        'declaracao' => 'Declaração',
        'recibo' => 'Recibo',
        'certificado' => 'Certificado',
        'outro' => 'Outro',
    ];
}
