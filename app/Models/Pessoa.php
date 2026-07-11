<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    protected $fillable = [
        'tipo', 'estrangeiro', 'nome', 'nome_social', 'cpf', 'cnpj', 'rg', 'orgao_emissor', 'passaporte',
        'data_nascimento', 'sexo', 'nacionalidade', 'naturalidade', 'origem', 'estado_civil', 'etnia',
        'nome_pai', 'nome_mae',
        'email', 'email_secundario', 'telefone', 'celular', 'instagram', 'facebook', 'linkedin',
        'cep', 'endereco', 'numero', 'complemento', 'bairro', 'caixa_postal', 'cidade', 'uf', 'pais',
        'religiao_id', 'profissao_id', 'local_trabalho', 'numero_conselho', 'lattes', 'escola_id',
        'foto', 'observacoes', 'observacoes_saude',
        'nao_receber_mensagens', 'blacklist', 'ignorar_reajuste', 'ativo',
        // documentos civis (doc aba 2)
        'rg_uf', 'rg_data_expedicao', 'certidao_matricula', 'certidao_numero', 'certidao_folha', 'certidao_livro',
        'reservista', 'titulo_eleitor', 'titulo_zona', 'titulo_municipio', 'titulo_data_expedicao',
        // contas a pagar (doc aba 8)
        'forma_pagamento_padrao', 'dia_pagamento',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'ativo' => 'boolean',
        'estrangeiro' => 'boolean',
        'nao_receber_mensagens' => 'boolean',
        'blacklist' => 'boolean',
        'ignorar_reajuste' => 'boolean',
        'rg_data_expedicao' => 'date',
        'titulo_data_expedicao' => 'date',
    ];

    public function religiao()
    {
        return $this->belongsTo(Religiao::class);
    }

    public function profissao()
    {
        return $this->belongsTo(Profissao::class);
    }

    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }

    public function aluno()
    {
        return $this->hasOne(Aluno::class);
    }

    public function profissional()
    {
        return $this->hasOne(Profissional::class);
    }

    public function necessidadesEspeciais()
    {
        return $this->belongsToMany(NecessidadeEspecial::class, 'pessoa_necessidades');
    }

    public function alergias()
    {
        return $this->belongsToMany(Alergia::class, 'pessoa_alergias');
    }

    public function telefones()
    {
        return $this->hasMany(TelefonePessoa::class);
    }

    public function contas()
    {
        return $this->hasMany(ContaPessoa::class);
    }
}
