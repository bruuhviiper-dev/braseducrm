<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TituloReceber extends Model
{
    protected $table = 'titulos_receber';

    protected $fillable = [
        'pessoa_id', 'matricula_id', 'categoria_receber_id', 'conta_bancaria_id',
        'desconto_incondicional_id', 'numero_documento', 'valor_original',
        'valor_desconto', 'valor_acrescimo', 'valor_pago',
        'data_emissao', 'data_vencimento', 'data_pagamento',
        'situacao', 'forma_pagamento', 'nosso_numero',
        'linha_digitavel', 'observacoes', 'gerado_por',
        'pagador', 'valor_juros', 'valor_multa', 'token_pagamento',
        'plano_conta_id', 'instrucoes_boleto', 'cobrar_juros_multa',
    ];

    protected $casts = [
        'valor_original' => 'decimal:2',
        'valor_desconto' => 'decimal:2',
        'valor_acrescimo' => 'decimal:2',
        'valor_pago' => 'decimal:2',
        'data_emissao' => 'date',
        'data_vencimento' => 'date',
        'data_pagamento' => 'date',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function categoriaReceber()
    {
        return $this->belongsTo(CategoriaReceber::class);
    }

    public function contaBancaria()
    {
        return $this->belongsTo(ContaBancaria::class);
    }
}
