<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Link de matrícula online gerado no card do CRM (autoatendimento, doc CRM). */
class LinkMatriculaOnline extends Model
{
    protected $table = 'links_matricula_online';

    protected $fillable = ['oportunidade_id', 'abertura_matricula_id', 'token', 'novo_checkout', 'expira_em'];

    protected $casts = ['novo_checkout' => 'boolean', 'expira_em' => 'datetime'];

    public function oportunidade()
    {
        return $this->belongsTo(Oportunidade::class);
    }

    public function abertura()
    {
        return $this->belongsTo(AberturaMatriculaOnline::class, 'abertura_matricula_id');
    }

    public function expirado(): bool
    {
        return $this->expira_em !== null && $this->expira_em->isPast();
    }
}
