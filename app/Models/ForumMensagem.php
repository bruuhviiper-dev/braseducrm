<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForumMensagem extends Model
{
    protected $table = 'forum_mensagens';
    protected $fillable = ['forum_ead_id', 'pessoa_id', 'mensagem', 'do_tutor'];
    protected $casts = ['do_tutor' => 'boolean'];

    public function forum(): BelongsTo
    {
        return $this->belongsTo(ForumEad::class, 'forum_ead_id');
    }

    public function pessoa(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class);
    }
}
