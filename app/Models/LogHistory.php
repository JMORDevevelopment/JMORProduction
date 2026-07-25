<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogHistory extends Model
{

    protected $table = 'log_history';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'ip',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
