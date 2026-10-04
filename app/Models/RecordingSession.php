<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A dated talk show recording session.
 * Original CI admin: Recording_sessions (stored in `events_calendar`).
 */
class RecordingSession extends Model
{
    protected $table = 'events_calendar';

    public $timestamps = false;

    protected $fillable = [
        'date_time',
        'name',
        'description',
        'user_id',
        'link',
    ];
}
