<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Reusable message bodies for talk show "send revision" emails.
 * Original CI admin: Talk_show_guests templates page (`templates` table).
 */
class TalkShowTemplate extends Model
{
    protected $table = 'templates';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'content',
    ];
}
