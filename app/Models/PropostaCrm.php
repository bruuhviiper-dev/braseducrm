<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropostaCrm extends Model
{
    protected $table = 'propostas_crm';

    protected $fillable = [
        'oportunidade_id', 'titulo', 'valor',
        'descricao', 'situacao', 'data_envio', 'validade',
        'desconto_percentual', 'aprovacao', 'criada_por', 'aprovada_por', 'motivo_reprovacao',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'desconto_percentual' => 'decimal:2',
        'data_envio' => 'date',
        'validade' => 'date',
    ];

    public function oportunidade()
    {
        return $this->belongsTo(Oportunidade::class);
    }

    public function criadaPor()
    {
        return $this->belongsTo(User::class, 'criada_por');
    }

    public function aprovadaPor()
    {
        return $this->belongsTo(User::class, 'aprovada_por');
    }
}
