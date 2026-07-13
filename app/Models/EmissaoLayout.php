<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Layout salvo de uma emissão (colunas + filtros), por usuário e função. */
class EmissaoLayout extends Model
{
    protected $table = 'emissao_layouts';

    protected $fillable = ['user_id', 'funcao_codigo', 'nome', 'colunas', 'filtros', 'padrao', 'compartilhado'];

    protected $casts = ['colunas' => 'array', 'filtros' => 'array', 'padrao' => 'boolean', 'compartilhado' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Layouts que o operador atual pode usar nesta função: os dele + os compartilhados por outros. */
    public static function layoutsDe(int $funcaoCodigo)
    {
        return static::where('funcao_codigo', $funcaoCodigo)
            ->where(fn ($q) => $q->where('user_id', auth()->id())->orWhere('compartilhado', true))
            ->orderBy('nome')->get();
    }
}
