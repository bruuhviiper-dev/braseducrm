<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profissional extends Model
{
    protected $table = 'profissionais';

    protected $fillable = ['pessoa_id', 'tipo_profissional_id', 'titularidade_id', 'registro_profissional', 'ativo', 'data_admissao', 'data_demissao', 'cargo', 'informacoes_adicionais', 'informacoes_curriculares', 'assinatura_path'];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function tipoProfissional()
    {
        return $this->belongsTo(TipoProfissional::class);
    }

    public function titularidade()
    {
        return $this->belongsTo(Titularidade::class);
    }
}
