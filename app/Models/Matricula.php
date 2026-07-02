<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    protected $fillable = [
        'numero_matricula', 'aluno_id', 'turma_id', 'turma_montada_id',
        'data_matricula', 'situacao', 'forma_ingresso_id', 'observacoes',
        'valor_total', 'desconto', 'num_parcelas', 'valor_parcela',
        'dia_vencimento', 'primeiro_vencimento', 'forma_pagamento_id',
    ];

    protected $casts = [
        'data_matricula' => 'date',
        'primeiro_vencimento' => 'date',
        'valor_total' => 'decimal:2',
        'desconto' => 'decimal:2',
        'valor_parcela' => 'decimal:2',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    /** Rótulo "NUMERO - Nome do Aluno" para selects/listagens. */
    public function getRotuloAttribute(): string
    {
        $nome = $this->aluno?->pessoa?->nome;
        return trim(($this->numero_matricula ?? ('#' . $this->id)) . ($nome ? ' - ' . $nome : ''));
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function turmaMontada()
    {
        return $this->belongsTo(TurmaMontada::class);
    }

    public function formaIngresso()
    {
        return $this->belongsTo(FormaIngresso::class);
    }

    public function formaPagamento()
    {
        return $this->belongsTo(FormaPagamento::class);
    }

    public function documentos()
    {
        return $this->hasMany(DocumentoMatricula::class);
    }

    public function notas()
    {
        return $this->hasMany(Nota::class);
    }

    public function frequencias()
    {
        return $this->hasMany(Frequencia::class);
    }
}
