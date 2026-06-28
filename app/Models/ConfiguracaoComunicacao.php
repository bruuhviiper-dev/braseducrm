<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoComunicacao extends Model
{
    protected $table = 'configuracoes_comunicacao';

    protected $fillable = [
        'remetente_nome', 'remetente_email', 'canal_padrao', 'assinatura',
        'enviar_aviso_vencimento', 'dias_aviso_vencimento', 'enviar_aviso_cobranca', 'configuracoes',
    ];

    protected $casts = [
        'enviar_aviso_vencimento' => 'boolean',
        'enviar_aviso_cobranca' => 'boolean',
        'dias_aviso_vencimento' => 'integer',
        'configuracoes' => 'json',
    ];

    public static function current(): self
    {
        return static::firstOrCreate([]);
    }
}
