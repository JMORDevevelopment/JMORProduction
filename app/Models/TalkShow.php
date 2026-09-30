<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TalkShow extends Model
{
    protected $table = 'talk_show';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'last_name',
        'company_name',
        'phone',
        'email',
        'bio',
        'work_name',
        'work_detail',
        'weblink',
        'interview',
        'ip',
        'date_time',
        'status',
    ];
}
