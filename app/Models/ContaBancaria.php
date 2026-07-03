<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContaBancaria extends Model
{
    protected $table = 'contas_bancarias';

    protected $fillable = [
        'nome', 'banco', 'agencia', 'conta',
        'tipo_conta', 'saldo_inicial', 'ativo',
        'tesouraria', 'recebimento_caixa', 'eh_conta_bancaria',
        'instituicao_ensino_id', 'ignorar_novos_planos', 'ocultar_saldo_painel',
        'desconsiderar_relatorios', 'descricao_resumida', 'data_saldo',
    ];

    protected $casts = [
        'saldo_inicial' => 'decimal:2',
        'ativo' => 'boolean',
    ];
}
