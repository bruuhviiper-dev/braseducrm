<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Calendario extends Model
{
    protected $table = 'calendarios';

    protected $fillable = ['nome', 'ano', 'periodo_letivo_id'];

    public function eventos(): HasMany
    {
        return $this->hasMany(CalendarioEvento::class)->orderBy('data');
    }

    /** Total de dias letivos do calendário. */
    public function diasLetivos(): int
    {
        return $this->eventos()->where('dia_letivo', true)->count();
    }
}
