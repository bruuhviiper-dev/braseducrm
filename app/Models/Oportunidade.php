<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oportunidade extends Model
{
    protected $table = 'oportunidades';

    protected $fillable = [
        'interessado_id', 'funil_id', 'etapa_funil_id', 'consultor_id',
        'curso_id', 'produto_servico_id', 'titulo', 'valor', 'situacao',
        'motivo_perda_id', 'motivo_ganho_id', 'motivo_pausa_id',
        'data_previsao_fechamento', 'data_fechamento', 'observacoes',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_previsao_fechamento' => 'date',
        'data_fechamento' => 'date',
    ];

    public function interessado()
    {
        return $this->belongsTo(Interessado::class);
    }

    public function funil()
    {
        return $this->belongsTo(Funil::class);
    }

    public function etapaFunil()
    {
        return $this->belongsTo(EtapaFunil::class);
    }

    public function consultor()
    {
        return $this->belongsTo(User::class, 'consultor_id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function produtoServico()
    {
        return $this->belongsTo(ProdutoServicoCrm::class, 'produto_servico_id');
    }

    public function motivoPerda()
    {
        return $this->belongsTo(MotivoPerda::class);
    }

    public function motivoGanho()
    {
        return $this->belongsTo(MotivoGanho::class);
    }

    public function motivoPausa()
    {
        return $this->belongsTo(MotivoPausa::class);
    }

    public function atividades()
    {
        return $this->hasMany(AtividadeOportunidade::class);
    }

    public function tags()
    {
        return $this->belongsToMany(TagCrm::class, 'oportunidade_tags');
    }
}
