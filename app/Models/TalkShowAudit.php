<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TalkShowAudit extends Model
{
    protected $table = 'talk_show_audits';

    public $timestamps = false;

    protected $fillable = [
        'talk_show_id',
        'action',
        'old_status',
        'new_status',
        'ip',
        'user_agent',
        'details',
        'created_at',
    ];

    public function talkShow(): BelongsTo
    {
        return $this->belongsTo(TalkShow::class, 'talk_show_id');
    }
}
