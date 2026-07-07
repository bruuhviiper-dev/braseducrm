<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssinaturaEletronica extends Model
{
    protected $table = 'assinaturas_eletronicas';

    protected $fillable = ['matricula_id', 'documento', 'arquivo', 'situacao', 'token'];

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }
}
