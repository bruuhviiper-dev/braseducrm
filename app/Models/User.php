<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nome', 'login', 'email', 'password',
        'grupo_operador_id', 'departamento_id', 'ativo',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'ativo' => 'boolean',
            'ultimo_acesso' => 'datetime',
        ];
    }

    public function grupoOperador()
    {
        return $this->belongsTo(GrupoOperador::class);
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function permissoes()
    {
        return $this->belongsToMany(Funcao::class, 'user_permissoes');
    }

    public function favoritos()
    {
        return $this->belongsToMany(Funcao::class, 'favoritos')->withPivot('ordem')->orderByPivot('ordem');
    }

    public function acessosRecentes()
    {
        return $this->belongsToMany(Funcao::class, 'acessos_recentes')->withPivot('acessado_em')->orderByPivot('acessado_em', 'desc');
    }

    public function atividades()
    {
        return $this->hasMany(Atividade::class);
    }

    public function temPermissao(int $codigoFuncao): bool
    {
        if ($this->grupoOperador && $this->grupoOperador->funcoes()->where('codigo', $codigoFuncao)->exists()) {
            return true;
        }
        return $this->permissoes()->where('codigo', $codigoFuncao)->exists();
    }
}
