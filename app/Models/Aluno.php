<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    protected $fillable = ['pessoa_id', 'ra', 'forma_ingresso_id', 'data_ingresso', 'ativo'];

    protected $casts = [
        'data_ingresso' => 'date',
        'ativo' => 'boolean',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function formaIngresso()
    {
        return $this->belongsTo(FormaIngresso::class);
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }
}
