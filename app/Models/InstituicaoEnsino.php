<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstituicaoEnsino extends Model
{
    protected $table = 'instituicoes_ensino';

    protected $fillable = [
        'nome', 'cnpj', 'razao_social', 'endereco', 'cidade', 'uf',
        'cep', 'telefone', 'email', 'site', 'logo', 'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];
}
