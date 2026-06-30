<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exemplar extends Model
{
    protected $table = 'exemplares';

    protected $fillable = [
        'obra_id', 'biblioteca_id', 'codigo', 'estado_conservacao_id', 'doador_pessoa_id',
        'tipo_aquisicao_id', 'valor_compra', 'data_aquisicao', 'copia_local', 'situacao',
    ];

    protected $casts = [
        'valor_compra' => 'decimal:2',
        'data_aquisicao' => 'date',
        'copia_local' => 'boolean',
    ];

    public const SITUACOES = ['disponivel', 'emprestado', 'reservado', 'indisponivel'];

    public function obra(): BelongsTo
    {
        return $this->belongsTo(Obra::class);
    }

    public function biblioteca(): BelongsTo
    {
        return $this->belongsTo(Biblioteca::class);
    }

    public function estadoConservacao(): BelongsTo
    {
        return $this->belongsTo(EstadoConservacao::class);
    }

    public function movimentacoes(): HasMany
    {
        return $this->hasMany(MovimentacaoExemplar::class);
    }
}
