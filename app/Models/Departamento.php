<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $fillable = ['nome', 'ativo'];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
