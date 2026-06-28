<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtividadeOportunidade extends Model
{
    protected $table = 'atividades_oportunidade';

    protected $fillable = [
        'oportunidade_id', 'evento_crm_id', 'responsavel_id',
        'titulo', 'descricao', 'data_agendamento', 'data_conclusao', 'situacao',
    ];

    protected $casts = [
        'data_agendamento' => 'datetime',
        'data_conclusao' => 'datetime',
    ];

    public function oportunidade()
    {
        return $this->belongsTo(Oportunidade::class);
    }

    public function eventoCrm()
    {
        return $this->belongsTo(EventoCrm::class);
    }

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }
}
