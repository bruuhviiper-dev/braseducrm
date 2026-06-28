<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LancamentoFinanceiro extends Model
{
    protected $table = 'lancamentos_financeiros';

    protected $fillable = [
        'conta_bancaria_id', 'plano_conta_id', 'tipo', 'valor',
        'data_lancamento', 'data_compensacao', 'descricao',
        'documento_referencia', 'titulo_receber_id', 'titulo_pagar_id',
        'conciliado', 'operador_id',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_lancamento' => 'date',
        'data_compensacao' => 'date',
        'conciliado' => 'boolean',
    ];
}
