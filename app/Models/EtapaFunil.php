<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EtapaFunil extends Model
{
    protected $table = 'etapas_funil';

    protected $fillable = ['funil_id', 'nome', 'ordem', 'cor', 'prazo_dias'];

    protected $casts = [
        'ordem' => 'integer',
        'prazo_dias' => 'integer',
    ];

    public function funil()
    {
        return $this->belongsTo(Funil::class);
    }

    public function oportunidades()
    {
        return $this->hasMany(Oportunidade::class);
    }
}
