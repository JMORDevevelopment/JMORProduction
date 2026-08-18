<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaInquiry extends Model
{
    public $timestamps = false;

    protected $table = 'media_inquiries';

    protected $fillable = [
        'media',
        'contact',
        'email',
        'phone',
        'story_concept',
        'press_deadline',
        'story_details',
        'best_contact',
        'protection_question',
        'media_status',
    ];
}
