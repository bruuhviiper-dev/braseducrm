<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interessado extends Model
{
    protected $table = 'interessados';

    protected $fillable = [
        'pessoa_id', 'nome', 'email', 'telefone', 'celular',
        'origem_id', 'categoria_id', 'curso_id', 'observacoes', 'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function origemInteressado()
    {
        return $this->belongsTo(OrigemInteressado::class, 'origem_id');
    }

    public function categoriaInteressado()
    {
        return $this->belongsTo(CategoriaInteressado::class, 'categoria_id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function oportunidades()
    {
        return $this->hasMany(Oportunidade::class);
    }
}
