<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoEad extends Model
{
    protected $table = 'videos_ead';

    protected $fillable = ['titulo', 'descricao', 'arquivo'];
}
