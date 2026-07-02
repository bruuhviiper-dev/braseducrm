<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TituloPagar extends Model
{
    protected $table = 'titulos_pagar';

    protected $fillable = [
        'pessoa_id', 'categoria_pagar_id', 'conta_bancaria_id', 'forma_pagamento', 'plano_conta_id',
        'numero_documento', 'linha_digitavel', 'descricao', 'valor_original', 'valor_pago',
        'data_emissao', 'data_vencimento', 'data_pagamento', 'referencia',
        'situacao', 'observacoes', 'gerado_por',
    ];

    protected $casts = [
        'valor_original' => 'decimal:2',
        'valor_pago' => 'decimal:2',
        'data_emissao' => 'date',
        'data_vencimento' => 'date',
        'data_pagamento' => 'date',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function categoriaPagar()
    {
        return $this->belongsTo(CategoriaPagar::class);
    }

    public function contaBancaria()
    {
        return $this->belongsTo(ContaBancaria::class);
    }

    public function planoConta()
    {
        return $this->belongsTo(PlanoContas::class, 'plano_conta_id');
    }

    public function rateios()
    {
        return $this->hasMany(RateioCentroCusto::class);
    }
}
