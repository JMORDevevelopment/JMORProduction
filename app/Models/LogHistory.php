<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogHistory extends Model
{
    // Matches database/migrations/..._create_log_history_table.php:
    // default `id` PK, Laravel timestamps, and a `user_id` FK to `user.user_id`.
    protected $table = 'log_history';

    protected $fillable = [
        'user_id',
        'ip',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
