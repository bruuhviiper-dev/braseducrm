<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Liberações extras do usuário além do departamento (doc: "no usuário consegue liberar mais funcionalidade"). */
class PermissaoUsuarioExtra extends Model
{
    protected $table = 'permissoes_usuario_extra';

    protected $fillable = ['user_id', 'funcao_codigo', 'acao'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
