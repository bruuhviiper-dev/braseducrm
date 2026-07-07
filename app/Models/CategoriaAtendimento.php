<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaAtendimento extends Model
{
    protected $table = 'categorias_atendimento';

    protected $fillable = ['nome', 'departamento_id'];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }
}
