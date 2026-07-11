<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Permissão por função+ação salva no departamento (doc: defaults do DP). Ação especial '_ocultar_menu'. */
class PermissaoDepartamento extends Model
{
    protected $table = 'permissoes_departamento';

    protected $fillable = ['departamento_id', 'funcao_codigo', 'acao'];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }
}
