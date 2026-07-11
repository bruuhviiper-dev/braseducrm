<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Aba Contas / PIX do Cadastro de Pessoa 11 (doc): para pagar ou reembolsar a pessoa. */
class ContaPessoa extends Model
{
    protected $table = 'contas_pessoa';

    protected $fillable = ['pessoa_id', 'tipo', 'chave_pix_tipo', 'chave_pix', 'banco', 'agencia', 'conta', 'tipo_conta', 'do_titular', 'nome_titular', 'cpf_titular'];

    protected $casts = ['do_titular' => 'boolean'];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
}
