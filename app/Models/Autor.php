<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    protected $table = 'autores';
    protected $fillable = ['nome', 'sobrenome'];

    public function getNomeCompletoAttribute(): string
    {
        return trim($this->nome . ' ' . ($this->sobrenome ?? ''));
    }
}
