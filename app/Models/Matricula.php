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
        'consultor_id', 'comissao_percentual',
        'previsao_conclusao', 'data_inicio_aulas', 'como_conheceu',
        'responsavel_financeiro_id', 'matriz_curricular_id', 'exibir_historico_prioritario',
    ];

    protected $casts = [
        'data_matricula' => 'date',
        'primeiro_vencimento' => 'date',
        'previsao_conclusao' => 'date',
        'data_inicio_aulas' => 'date',
        'valor_total' => 'decimal:2',
        'desconto' => 'decimal:2',
        'valor_parcela' => 'decimal:2',
        'exibir_historico_prioritario' => 'boolean',
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

    /** Vendedor da matrícula (222 — Cálculo de Comissões). */
    public function consultor()
    {
        return $this->belongsTo(User::class, 'consultor_id');
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

    public function responsavelFinanceiro()
    {
        return $this->belongsTo(Pessoa::class, 'responsavel_financeiro_id');
    }

    public function matrizCurricular()
    {
        return $this->belongsTo(MatrizCurricular::class);
    }

    public function enturmacoes()
    {
        return $this->hasMany(Enturmacao::class);
    }

    public function movimentacoes()
    {
        return $this->hasMany(MovimentacaoMatricula::class)->orderByDesc('id');
    }

    public function enades()
    {
        return $this->hasMany(EnadeRegistro::class);
    }

    public function assinaturasEletronicas()
    {
        return $this->hasMany(AssinaturaEletronica::class);
    }

    public function entregasDocumento()
    {
        return $this->hasMany(EntregaDocumento::class);
    }

    public function titulosReceber()
    {
        return $this->hasMany(TituloReceber::class);
    }

    public function horasComplementares()
    {
        return $this->hasMany(HoraComplementar::class);
    }
}
