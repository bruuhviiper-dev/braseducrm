<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcaoAutomaticaCrm extends Model
{
    protected $table = 'acoes_automaticas_crm';
    protected $fillable = ['nome', 'gatilho', 'acao', 'detalhes', 'ativo', 'funil_destino_id', 'responsavel_destino_id'];
    protected $casts = ['ativo' => 'boolean'];

    public const GATILHOS = [
        'novo_interessado' => 'Novo interessado cadastrado',
        'nova_oportunidade' => 'Nova oportunidade criada',
        'mudanca_etapa' => 'Mudança de etapa no funil',
        'oportunidade_ganha' => 'Oportunidade ganha',
        'oportunidade_perdida' => 'Oportunidade perdida',
    ];

    public const ACOES = [
        'criar_atividade' => 'Criar atividade/tarefa',
        'enviar_email' => 'Enviar e-mail',
        'notificar_consultor' => 'Notificar consultor',
        'mover_etapa' => 'Mover para etapa',
        'duplicar_oportunidade' => 'Duplicar oportunidade para outro funil (pós-vendas)',
    ];

    public function funilDestino()
    {
        return $this->belongsTo(Funil::class, 'funil_destino_id');
    }

    public function responsavelDestino()
    {
        return $this->belongsTo(User::class, 'responsavel_destino_id');
    }
}
