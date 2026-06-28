<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscricao extends Model
{
    protected $table = 'inscricoes';

    protected $fillable = [
        'pessoa_id', 'abertura_matricula_id', 'nome', 'email',
        'telefone', 'cpf', 'situacao', 'pagamento_confirmado',
        'contrato_assinado', 'cupom_desconto_id',
    ];

    protected $casts = [
        'pagamento_confirmado' => 'boolean',
        'contrato_assinado' => 'boolean',
    ];

    public function abertura()
    {
        return $this->belongsTo(AberturaMatriculaOnline::class, 'abertura_matricula_id');
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function cupom()
    {
        return $this->belongsTo(CupomDesconto::class, 'cupom_desconto_id');
    }
}
