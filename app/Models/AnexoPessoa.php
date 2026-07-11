<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Aba Anexos do Cadastro de Pessoa 11 (doc): arquivos digitalizados com fluxo de homologação. */
class AnexoPessoa extends Model
{
    protected $table = 'anexos_pessoa';

    protected $fillable = ['pessoa_id', 'tipo_documento', 'arquivo', 'descricao', 'user_id', 'situacao', 'motivo_rejeicao'];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
