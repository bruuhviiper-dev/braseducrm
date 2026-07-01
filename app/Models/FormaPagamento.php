<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormaPagamento extends Model
{
    protected $table = 'formas_pagamento';
    protected $fillable = ['nome', 'tipo', 'ativo'];
    protected $casts = ['ativo' => 'boolean'];

    public const TIPOS = [
        'dinheiro' => 'Dinheiro',
        'cartao_credito' => 'Cartão de Crédito',
        'cartao_debito' => 'Cartão de Débito',
        'boleto' => 'Boleto',
        'pix' => 'PIX',
        'cheque' => 'Cheque',
        'transferencia' => 'Transferência',
    ];
}
