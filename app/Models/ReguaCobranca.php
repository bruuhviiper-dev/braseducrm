<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReguaCobranca extends Model
{
    protected $table = 'reguas_cobranca';

    protected $fillable = ['tipo', 'dias', 'canal', 'mensagem', 'filtrar_ja_notificados', 'ativo'];

    protected $casts = ['filtrar_ja_notificados' => 'boolean', 'ativo' => 'boolean'];

    public const TIPOS = [
        'antecedencia' => 'Aviso de vencimento (dias ANTES)',
        'atraso' => 'Cobrança por parcela (dias APÓS o vencimento)',
        'pagamento' => 'Aviso de pagamento (confirmação da baixa)',
    ];

    public function envios()
    {
        return $this->hasMany(ReguaEnvio::class, 'regua_cobranca_id');
    }
}
