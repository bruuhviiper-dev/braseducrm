<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cheque extends Model
{
    protected $table = 'cheques';

    protected $fillable = [
        'tipo', 'numero', 'banco_id', 'agencia', 'conta', 'emitente',
        'valor', 'bom_para', 'situacao', 'motivo_devolucao_id', 'observacao',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'bom_para' => 'date',
    ];

    public const TIPOS = ['recebido' => 'Recebido', 'emitido' => 'Emitido'];

    public const SITUACOES = [
        'carteira' => 'Em carteira',
        'depositado' => 'Depositado',
        'compensado' => 'Compensado',
        'devolvido' => 'Devolvido',
        'repassado' => 'Repassado',
    ];

    public function banco(): BelongsTo
    {
        return $this->belongsTo(Banco::class);
    }

    public function motivoDevolucao(): BelongsTo
    {
        return $this->belongsTo(MotivoDevolucaoCheque::class, 'motivo_devolucao_id');
    }
}
