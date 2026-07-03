<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interessado extends Model
{
    protected $table = 'interessados';

    protected $fillable = [
        'pessoa_id', 'nome', 'e_empresa', 'nao_enviar_mensagens', 'email', 'cpf', 'telefone', 'celular', 'codigo_pais',
        'origem_id', 'responsavel_id', 'categoria_id', 'profissao_id', 'cidade', 'formacao',
        'instagram', 'facebook', 'curso_id', 'observacoes', 'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'e_empresa' => 'boolean',
        'nao_enviar_mensagens' => 'boolean',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    public function profissao()
    {
        return $this->belongsTo(Profissao::class);
    }

    public function contatos()
    {
        return $this->hasMany(ContatoInteressado::class);
    }

    public function origemInteressado()
    {
        return $this->belongsTo(OrigemInteressado::class, 'origem_id');
    }

    public function categoriaInteressado()
    {
        return $this->belongsTo(CategoriaInteressado::class, 'categoria_id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function oportunidades()
    {
        return $this->hasMany(Oportunidade::class);
    }
}
