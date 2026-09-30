<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TalkShowSetting extends Model
{
    protected $table = 'talk_show_settings';

    public $timestamps = false;

    protected $fillable = [
        'price',
        'question',
    ];
}
