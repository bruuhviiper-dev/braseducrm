<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DescontoCondicionalItem extends Model
{
    protected $table = 'desconto_condicional_itens';

    protected $fillable = ['desconto_condicional_id', 'dias', 'valor'];

    protected $casts = ['valor' => 'decimal:2'];

    public function desconto()
    {
        return $this->belongsTo(DescontoCondicional::class, 'desconto_condicional_id');
    }
}
