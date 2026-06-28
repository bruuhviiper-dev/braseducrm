<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagCrm extends Model
{
    protected $table = 'tags_crm';

    protected $fillable = ['nome', 'cor'];
}
