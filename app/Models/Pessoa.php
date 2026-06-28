<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    protected $fillable = [
        'tipo', 'nome', 'nome_social', 'cpf', 'cnpj', 'rg', 'orgao_emissor',
        'data_nascimento', 'sexo', 'nacionalidade', 'naturalidade', 'estado_civil',
        'email', 'email_secundario', 'telefone', 'celular',
        'cep', 'endereco', 'numero', 'complemento', 'bairro', 'cidade', 'uf', 'pais',
        'religiao_id', 'profissao_id', 'escola_id', 'foto', 'observacoes', 'ativo',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'ativo' => 'boolean',
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
}
