<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanoContas extends Model
{
    protected $table = 'plano_contas';

    protected $fillable = [
        'codigo', 'nome', 'pai_id', 'tipo',
        'natureza', 'nivel', 'ordem', 'ativo', 'mascara_filhos', 'tesouraria', 'identificador_integracao',
    ];

    protected $casts = [
        'nivel' => 'integer',
        'ordem' => 'integer',
        'ativo' => 'boolean',
    ];

    public function pai()
    {
        return $this->belongsTo(PlanoContas::class, 'pai_id');
    }

    public function filhos()
    {
        return $this->hasMany(PlanoContas::class, 'pai_id');
    }

    public function filhosRecursivos()
    {
        return $this->filhos()->with('filhosRecursivos');
    }
}
