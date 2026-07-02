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
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'ativo' => 'boolean',
        'estrangeiro' => 'boolean',
        'nao_receber_mensagens' => 'boolean',
        'blacklist' => 'boolean',
        'ignorar_reajuste' => 'boolean',
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
