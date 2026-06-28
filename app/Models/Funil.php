<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Funil extends Model
{
    protected $table = 'funis';

    protected $fillable = ['nome', 'padrao', 'ativo', 'configuracoes'];

    protected $casts = [
        'padrao' => 'boolean',
        'ativo' => 'boolean',
        'configuracoes' => 'json',
    ];

    public function etapas()
    {
        return $this->hasMany(EtapaFunil::class);
    }

    public function oportunidades()
    {
        return $this->hasMany(Oportunidade::class);
    }
}
